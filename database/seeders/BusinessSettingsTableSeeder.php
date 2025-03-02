<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BusinessSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('business_settings')->insert([
            ['id' => 3, 'key' => 'restaurant_name', 'value' => 'Glilat Gold', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'key' => 'currency', 'value' => 'USD', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'key' => 'logo', 'value' => 'logo.png', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'key' => 'mail_config', 'value' => '{"status":0,"name":"Delivery APP","host":"mail.demo.com","driver":"smtp","port":"587","username":"info@demo.com","email_id":"info@demo.com","encryption":"tls","password":"demo"}', 'created_at' => null, 'updated_at' => Carbon::parse('2024-01-03 17:09:14')],
            ['id' => 11, 'key' => 'phone', 'value' => '+01747413273', 'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'key' => 'footer_text', 'value' => 'copyright  ', 'created_at' => null, 'updated_at' => null],
            ['id' => 14, 'key' => 'address', 'value' => 'Hazi osman gani lane', 'created_at' => null, 'updated_at' => null],
            ['id' => 15, 'key' => 'email_address', 'value' => 'nipon34.bd@gmail.com', 'created_at' => null, 'updated_at' => null],
            ['id' => 19, 'key' => 'terms_and_conditions', 'value' => '<div class=\"ql-editor\" data-gramm=\"false\" contenteditable=\"true\"><h1>Terms and Condition</h1><p><br></p><ol><li>Hello, terms and conditions.......</li><li>Hello</li></ol></div><div class=\"ql-clipboard\" contenteditable=\"true\" tabindex=\"-1\"></div><div class=\"ql-tooltip ql-hidden\"><a class=\"ql-preview\" target=\"_blank\" href=\"about:blank\"></a><input type=\"text\" data-formula=\"e=mc^2\" data-link=\"https://quilljs.com\" data-video=\"Embed URL\"><a class=\"ql-action\"></a><a class=\"ql-remove\"></a></div>', 'created_at' => null, 'updated_at' => Carbon::parse('2021-02-11 18:31:50')],
            ['id' => 22, 'key' => 'push_notification_key', 'value' => 'demo', 'created_at' => null, 'updated_at' => null],
            ['id' => 24, 'key' => 'order_pending_message', 'value' => '{"status":1,"message":"Your order has been placed successfully."}', 'created_at' => null, 'updated_at' => null],
            ['id' => 36, 'key' => 'about_us', 'value' => '<p>fffvawvwvw</p>', 'created_at' => null, 'updated_at' => Carbon::parse('2021-05-31 09:26:16')],
            ['id' => 37, 'key' => 'privacy_policy', 'value' => '<div class=\"ql-editor\" data-gramm=\"false\" contenteditable=\"true\" spellcheck=\"false\"><p>hello</p></div><grammarly-extension data-grammarly-shadow-root=\"true\" style=\"position: absolute; top: 0px; left: -1px; pointer-events: none; z-index: auto;\" class=\"cGcvT\"></grammarly-extension><grammarly-extension data-grammarly-shadow-root=\"true\" style=\"mix-blend-mode: darken; position: absolute; top: 0px; left: -1px; pointer-events: none; z-index: auto;\" class=\"cGcvT\"></grammarly-extension><div class=\"ql-clipboard\" contenteditable=\"true\" tabindex=\"-1\"></div><div class=\"ql-tooltip ql-hidden\"><a class=\"ql-preview\" target=\"_blank\" href=\"about:blank\"></a><input type=\"text\" data-formula=\"e=mc^2\" data-link=\"https://quilljs.com\" data-video=\"Embed URL\"><a class=\"ql-action\"></a><a class=\"ql-remove\"></a></div>', 'created_at' => null, 'updated_at' => Carbon::parse('2021-05-23 09:02:03')],
            ['id' => 38, 'key' => 'senang_pay', 'value' => '{"status":"0","secret_key":null,"merchant_id":null}', 'created_at' => null, 'updated_at' => Carbon::parse('2021-06-12 04:59:51')],
            ['id' => 40, 'key' => 'point_per_currency', 'value' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 41, 'key' => 'language', 'value' => '["en","ar"]', 'created_at' => null, 'updated_at' => null],
            ['id' => 42, 'key' => 'time_zone', 'value' => 'Pacific/Midway', 'created_at' => null, 'updated_at' => null],
            ['id' => 43, 'key' => 'internal_point', 'value' => '{"status":null}', 'created_at' => Carbon::parse('2021-06-01 04:36:10'), 'updated_at' => Carbon::parse('2021-06-01 04:36:10')],
            ['id' => 49, 'key' => 'pagination_limit', 'value' => '10', 'created_at' => null, 'updated_at' => null],
            ['id' => 52, 'key' => 'play_store_config', 'value' => '{"status":"","link":"","min_version":""}', 'created_at' => null, 'updated_at' => null],
            ['id' => 53, 'key' => 'app_store_config', 'value' => '{"status":"","link":"","min_version":""}', 'created_at' => null, 'updated_at' => null],
            ['id' => 60, 'key' => 'cookies', 'value' => '{"status":"1","text":"Allow Cookies for this site"}', 'created_at' => null, 'updated_at' => null],
            ['id' => 61, 'key' => 'fav_icon', 'value' => 'logo3.png', 'created_at' => null, 'updated_at' => null],
            ['id' => 62, 'key' => 'map_api_server_key', 'value' => '', 'created_at' => null, 'updated_at' => null],
            ['id' => 63, 'key' => 'google_social_login', 'value' => '1', 'created_at' => null, 'updated_at' => null],
            ['id' => 64, 'key' => 'facebook_social_login', 'value' => '1', 'created_at' => null, 'updated_at' => null],
            ['id' => 65, 'key' => 'whatsapp', 'value' => '{"status":"0","number":""}', 'created_at' => null, 'updated_at' => null],
            ['id' => 66, 'key' => 'telegram', 'value' => '{"status":"0","user_name":""}', 'created_at' => null, 'updated_at' => null],
            ['id' => 67, 'key' => 'messenger', 'value' => '{"status":"0","user_name":""}', 'created_at' => null, 'updated_at' => null],
            ['id' => 68, 'key' => 'app_logo', 'value' => 'logo2.png', 'created_at' => null, 'updated_at' => null],
            ['id' => 69, 'key' => 'country', 'value' => 'BD', 'created_at' => null, 'updated_at' => null],
        ]);
    }
}
