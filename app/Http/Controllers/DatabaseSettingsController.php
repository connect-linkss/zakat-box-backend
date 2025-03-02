<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerExport;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DatabaseSettingsController extends Controller
{
    public function __construct(
        private CustomerExport $backup,
    ) {}

    /**
     * @return View
     */
    public function databaseIndex(Request $request): View
    {
        $queryParam = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $customers = $this->backup->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $queryParam = ['search' => $request['search']];
        } else {
            $customers = $this->backup;
        }
        $customers = $customers->latest()->paginate(10)->appends($queryParam);
        return view('admin-views.business-settings.db-index', compact('customers', 'search'));
    }

    /**
     * Generate a new database backup.
     *
     * @return RedirectResponse
     */
    public function generateBackup(): RedirectResponse
    {
        // Retrieve users with is_backup = 1
        $users = User::where('is_backup', 1)->get();
    
        if ($users->isEmpty()) {
            return back()->withErrors('No users found to backup.');
        }
    
        // Initialize the VCF content
        $vcfContent = '';
        foreach ($users as $user) {
            $vcfContent .= "BEGIN:VCARD\n";
            $vcfContent .= "VERSION:3.0\n";
            $vcfContent .= "FN:" . $user->name . "\n"; // Full name
            if (!empty($user->phone)) {
                $vcfContent .= "TEL;TYPE=CELL:" . $user->phone . "\n"; // Phone number
            }
            if (!empty($user->email)) {
                $vcfContent .= "EMAIL:" . $user->email . "\n"; // Email
            }
            if (!empty($user->address)) {
                $vcfContent .= "ADR;TYPE=HOME:;;" . $user->address . "\n"; // Address
            }
            $vcfContent .= "END:VCARD\n";
        }
    
        // Define the file name
        $fileName = 'backup_' . now()->format('Y_m_d_H_i_s') . '.vcf';
        $directory = public_path('backup');
    
        // Ensure the backup directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    
        // Save the VCF file
        $filePath = $directory . '/' . $fileName;
        file_put_contents($filePath, $vcfContent);
    
        // Save file information to the database
        CustomerExport::create([
            'file' => $fileName,
        ]);
    
        // Update is_backup to 2 for the users
        User::where('is_backup', 1)->update(['is_backup' => 2]);
    
        return back()->with('success', 'Backup generated successfully.');
    }
    

    /**
     * Download a backup file by ID.
     *
     * @param int $id
     * @return BinaryFileResponse|RedirectResponse
     */
    public function downloadBackup(int $id): BinaryFileResponse|RedirectResponse
    {
        // Find the backup by ID
        $backup = CustomerExport::find($id);

        if (!$backup || !file_exists(public_path('backup/' . $backup->file))) {
            return redirect()->back()->withErrors('File not found.');
        }

        // Define the file path
        $filePath = public_path('backup/' . $backup->file);

        return response()->download($filePath);
    }
}
