<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;


class BusinessSettingsController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting,
    ) {}

    /**
     * @return Application|Factory|View
     */
    public function BusinessSetup(): View|Factory|Application
    {
        if (!$this->business_setting->where(['key' => 'minimum_order_value'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'minimum_order_value'], [
                'value' => 1
            ]);
        }

        if (!$this->business_setting->where(['key' => 'fav_icon'])->first()) {
            DB::table('business_settings')->updateOrInsert(['key' => 'fav_icon'], [
                'value' => ''
            ]);
        }

        $logo = Helpers::get_business_settings('logo');
        $logo = url('') . '/public/images/' .  $logo;

        $app_logo = Helpers::get_business_settings('app_logo');
        $app_logo = url('') . '/public/images/' . $app_logo;

        $fav_icon = Helpers::get_business_settings('fav_icon');
        $fav_icon = url('') . '/public/images/' . $fav_icon;

        return view('admin-views.business-settings.restaurant-index', compact('logo', 'app_logo', 'fav_icon'));
    }

    /**
     * @param $side
     * @return JsonResponse
     */
    public function currencySymbolPosition($side): JsonResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $side
        ]);
        return response()->json(['message' => translate("Symbol position is ") . $side]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function BusinessSetupUpdate(Request $request): RedirectResponse
    {

        DB::table('business_settings')->updateOrInsert(['key' => 'restaurant_name'], [
            'value' => $request->restaurant_name
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'email_address'], [
            'value' => $request['email']
        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);


        DB::table('business_settings')->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function mailIndex(): View|Factory|Application
    {
        return view('admin-views.business-settings.mail-index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function mailSend(Request $request): JsonResponse
    {
        $response_flag = 0;
        try {
            $emailServices = Helpers::get_business_settings('mail_config');

            if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
                $response_flag = 1;
            }
        } catch (\Exception $exception) {
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function mailConfig(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'mail_config'])->update([
            'value' => json_encode([
                "status" => 1,
                "name" => $request['name'],
                "host" => $request['host'],
                "driver" => $request['driver'],
                "port" => $request['port'],
                "username" => $request['username'],
                "email_id" => $request['email'],
                "encryption" => $request['encryption'],
                "password" => $request['password']
            ])
        ]);
        Toastr::success(translate('Configuration updated successfully!'));
        return back();
    }

    /**
     * @param $status
     * @return JsonResponse
     */
    public function mailConfigStatus($status): JsonResponse
    {
        $data = Helpers::get_business_settings('mail_config');
        $data['status'] = $status == '1' ? 1 : 0;

        $this->business_setting->where(['key' => 'mail_config'])->update([
            'value' => $data,
        ]);
        return response()->json(['message' => 'Mail config status updated']);
    }



    public function termsAndConditions(): View|Factory|Application
    {
        $tnc = $this->business_setting->where(['key' => 'terms_and_conditions'])->first();
        if (!$tnc) {
            $this->business_setting->insert([
                'key' => 'terms_and_conditions',
                'value' => ''
            ]);
        }
        return view('admin-views.business-settings.terms-and-conditions', compact('tnc'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function termsAndConditionsUpdate(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'terms_and_conditions'])->update([
            'value' => $request->tnc
        ]);
        Toastr::success(translate('Terms and Conditions updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function privacyPolicy(): View|Factory|Application
    {
        $data = $this->business_setting->where(['key' => 'privacy_policy'])->first();
        if (!$data) {
            $data = [
                'key' => 'privacy_policy',
                'value' => '',
            ];
            $this->business_setting->insert($data);
        }
        return view('admin-views.business-settings.privacy-policy', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function privacyPolicyUpdate(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'privacy_policy'])->update([
            'value' => $request->privacy_policy,
        ]);

        Toastr::success(translate('Privacy policy updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function aboutUs(): View|Factory|Application
    {
        $data = $this->business_setting->where(['key' => 'about_us'])->first();
        if (!$data) {
            $data = [
                'key' => 'about_us',
                'value' => '',
            ];
            $this->business_setting->insert($data);
        }
        return view('admin-views.business-settings.about-us', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function aboutUsUpdate(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'about_us'])->update([
            'value' => $request->about_us,
        ]);

        Toastr::success(translate('About us updated!'));
        return back();
    }


    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function recaptchaIndex(Request $request): View|Factory|Application
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function recaptchaUpdate(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'] == 'on' ? 1 : 0,
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function appSettingIndex(): View|Factory|Application
    {
        return View('admin-views.business-settings.app-setting-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function appSettingUpdate(Request $request): RedirectResponse
    {
        if ($request->platform == 'android') {
            DB::table('business_settings')->updateOrInsert(['key' => 'play_store_config'], [
                'value' => json_encode([
                    'status' => $request['play_store_status'],
                    'link' => $request['play_store_link'],
                    'min_version' => $request['android_min_version'],

                ]),
            ]);

            Toastr::success(translate('Updated Successfully for Android'));
            return back();
        }

        if ($request->platform == 'ios') {
            DB::table('business_settings')->updateOrInsert(['key' => 'app_store_config'], [
                'value' => json_encode([
                    'status' => $request['app_store_status'],
                    'link' => $request['app_store_link'],
                    'min_version' => $request['ios_min_version'],
                ]),
            ]);

            Toastr::success(translate('Updated Successfully for IOS'));
            return back();
        }


        Toastr::error(translate('Updated failed'));
        return back();
    }


    /**
     * @return Application|Factory|View
     */
    public function otpIndex(): Factory|View|Application
    {
        return view('admin-views.business-settings.otp-setup');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateOtp(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_otp_hit'], [
            'value' => $request['maximum_otp_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'otp_resend_time'], [
            'value' => $request['otp_resend_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_block_time'], [
            'value' => $request['temporary_block_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_login_hit'], [
            'value' => $request['maximum_login_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_login_block_time'], [
            'value' => $request['temporary_login_block_time'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @return Application|Factory|View
     */
    public function cookiesSetup(): Factory|View|Application
    {
        return view('admin-views.business-settings.cookies-setup');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cookiesSetupUpdate(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'cookies'], [
            'value' => json_encode([
                'status' => $request['status'],
                'text' => $request['text'],
            ])
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }



    /**
     * @param $medium
     * @param $status
     * @return JsonResponse
     */
    public function changeSocialLoginStatus($medium, $status): JsonResponse
    {
        if ($medium == 'google') {
            DB::table('business_settings')->updateOrInsert(['key' => 'google_social_login'], [
                'value' => $status
            ]);
        } elseif ($medium == 'facebook') {
            DB::table('business_settings')->updateOrInsert(['key' => 'facebook_social_login'], [
                'value' => $status
            ]);
        }
        return response()->json(['message' => 'Status updated']);
    }
}
