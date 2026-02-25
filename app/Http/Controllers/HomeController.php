<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Banner;
use App\Models\Pages;
use App\Models\Bcategory;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Sponsor;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Service;

class HomeController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $settingDetail = Setting::get()->toArray();
        for ($s=0; $s < count($settingDetail); $s++) {
            if (!defined($settingDetail[$s]['setting_name'])) {
                define($settingDetail[$s]['setting_name'], $settingDetail[$s]['setting_value']);
            }
        }
    }

    public function index()
    {
        try {
            $pagesDetail = Pages::where('page_id', 1)->first();
            if (!$pagesDetail) {
                return redirect('/404');
            }
            $bannerDetail = Banner::where(['banner_status' => '1'])->orderBy('banner_order')->get()->toArray();
            $testimonialDetail = Testimonial::where('testimonial_status', 1)->orderBy('testimonial_order')->get()->toArray();
            $sponsorDetail = Sponsor::where('sponsor_status', 1)->orderBy('sponsor_order')->get()->toArray();
            $ourFeatureDetail = Service::where(['service_status' => '1', 'service_type' => '0'])->orderBy('service_order')->get()->toArray();
            $whyChooseDetail = Service::where(['service_status' => '1', 'service_type' => '1'])->orderBy('service_order')->get()->toArray();
            $blogDetail = Blog::where(['blog_status' => '1'])->orderBy('blog_order', 'DESC')->take(6)->get()->toArray();

            return view('home')->with([
                'pagesDetail' => $pagesDetail,
                'bannerDetail' => $bannerDetail,
                'testimonialDetail' => $testimonialDetail,
                'sponsorDetail' => $sponsorDetail,
                'ourFeatureDetail' => $ourFeatureDetail,
                'whyChooseDetail' => $whyChooseDetail,
                'blogDetail' => $blogDetail
            ]);
        } catch (\Throwable $e) {
            // Logging error
            Log::error('HomeController@index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect('/404');
        }
    }

    public function page($slug)
    {
        try {
            $pagesDetail = Pages::where('page_slug', trim($slug))->firstOrFail();
            if (!$pagesDetail) {
                return redirect('/404');
            }
            return view('page', compact('pagesDetail'));
        } catch (\Exception $e) {
            return redirect('/404');
        }
    }

    public function contact()
    {
        try {
            $pagesDetail = Pages::findOrFail(3);
            return view('contact', compact('pagesDetail'));
        } catch (\Exception $e) {
            return redirect('/404');
        }
    }

    public function contact_insert(Request $request)
    {
        try {
            $pagesDetail = Pages::where('page_id', 4)->first();
            if(!$pagesDetail){
                return redirect('/404');
            }
            $lastOrder = Contact::orderBy('contact_order', 'DESC')->first();
            Contact::create([
                'contact_name' => ucwords(strtolower($_POST['fname'])) .' '. ucwords(strtolower($_POST['lname'])),
                'contact_email' => strtolower($_POST['email']),
                'contact_country' => $_POST['country'],
                'contact_prefix' => $_POST['prefix'],
                'contact_mobile' => $_POST['mobile'],
                'contact_subject' => $_POST['subject'],
                'contact_message' => $_POST['message'],
                'contact_ip' => $request->ip(),
                'contact_order' => (!empty($lastOrder)) ? $lastOrder->contact_order + 1 : 1,
                'contact_status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            //$fromEmail        = FROM_EMAIL;
            $fromName           = 'YAARIOKE';
            $subjectUser        = "Thank You for reaching out: YAARIOKE is here to help !";
            $subjectAdmin       = "New Inquiry from YAARIOKE By " . ucwords(strtolower($_POST['fname'])) .' '. ucwords(strtolower($_POST['lname']));

            $messageHeaderUser  =
                "<tr>
                        <td style='font-size:15px'>Hello ".ucwords(strtolower($_POST['fname'])) .' '. ucwords(strtolower($_POST['lname'])).",</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>Thank you for reaching out to us.<br>We appreciate your interest in YAARIOKE. Our team is currently reviewing your inquiry and we will get back to you shortly.<br>If you have any urgent questions or concerns, feel free to contact us directly at <a href='tel:919509914499'>+91 950 991 4499</a>.</td>
                    </tr><br>";

            $messageHeaderAdmin =
                "<tr>
                        <td style='font-size:15px'>Dear Administrator,</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>".ucwords(strtolower($_POST['fname'])) .' '. ucwords(strtolower($_POST['lname']))." submitted contact form from website.</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>Details are below</td>
                    </tr><br>";

            $message            =
                "<tr>
                        <td style=\"font-size:15px; background:#dbeef4;\">
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td width=\"150\"><strong>Full Name: </strong></td>
                                <!--<td>&nbsp;</td>-->
                                <td align=\"left\" valign=\"top\">".ucwords(strtolower($_POST['fname'])) .' '. ucwords(strtolower($_POST['lname']))."</td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"font-size:15px;\">
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td width=\"150\"><strong>Mobile: </strong></td>
                               <!-- <td>&nbsp;</td>-->
                                <td align=\"left\" valign=\"top\">".$_POST['mobile']."</td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"font-size:15px; background:#dbeef4;\">
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td width=\"150\"><strong>Email: </strong></td>
                               <!-- <td>&nbsp;</td>-->
                                <td align=\"left\" valign=\"top\">".$_POST['email']."</td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"font-size:15px;\">
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td width=\"150\"><strong>Subject: </strong></td>
                               <!-- <td>&nbsp;</td>-->
                                <td align=\"left\" valign=\"top\">" . $_POST['subject'] . "</td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"font-size:15px; background:#dbeef4;\">
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td width=\"150\"><strong>Message: </strong></td>
                                <!--<td>&nbsp;</td>-->
                                <td align=\"left\" valign=\"top\">".$_POST['message']."</td>
                            </tr>
                            </table>
                        </td>
                    </tr>";

            $messageFooterUser  =
                "<br><tr>
                        <td style='font-size:15px'>Thank you,</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>YAARIOKE Team.</td>
                    </tr>";
            $messageFooterAdmin =
                "<br><br><tr>
                        <td style='font-size:15px'>Thank you,</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>YAARIOKE Team.</td>
                    </tr>";

            //mail sent to user
            //$this->sendMail($fromEmail, $_POST['email'], $fromName, ucwords(strtolower($_POST['fname'])) .' '. ucwords(strtolower($_POST['lname'])), $subjectUser, $messageHeaderUser . $message . $messageFooterUser);

            //mail sent to admin
            //if (ADMIN_EMAIL != "") {
                //$this->sendMail($fromEmail, ADMIN_EMAIL, $fromName, '', $subjectAdmin, $messageHeaderAdmin . $message . $messageFooterAdmin);
            //}
            return 'success';
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Contact Form Error: ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect('/404');
        }
    }

    public function faqs() {
        $pagesDetail = Pages::where('page_id', 5)->first();
        if(!$pagesDetail){
            return redirect('/404');
        }
        $faqDetail = Faq::where('faq_status', '1')->orderBy('faq_order')->get()->toArray();
        return view('faq')->with(['pagesDetail' => $pagesDetail, 'faqDetail' => $faqDetail]);
    }

    public function blog(Request $request)
    {
        try {
            //print_r($request->all()); die;
            //Start Pagination
            $pageno = 1;
            $limit = 9;
            $start = 0;
            if(isset($request['page']) && $request['page']!='') {
                $pageno = $request['page'];
            }
            $start = ($pageno-1) * $limit;
            $pagecount = $limit * $pageno;
            //End Pagination

            $bcategoryNameArray = [];
            $pagesDetail = Pages::where('page_id', 2)->first();
            if(!$pagesDetail){
                return redirect('/404');
            }
            $bcategoryDetail = Bcategory::get(['bcategory_id', 'bcategory_title'])->toArray();
            for($b=0; $b < count($bcategoryDetail); $b++) {
                $bcategoryNameArray[$bcategoryDetail[$b]['bcategory_id']] = $bcategoryDetail[$b]['bcategory_title'];
            }
            //get category wise blog
            if (isset($request['act']) && $request['act']=='load_blog') {
                //get all blog
                $blogDetail = Blog::where('blog_status', '1')->orderBy('blog_order', 'DESC')->skip($start)->take($limit)->get()->toArray();
                if(is_array($blogDetail) && count($blogDetail) > 0) { for($b=0; $b < count($blogDetail); $b++) { ?>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <!-- Blog -->
                        <div class="featured-venues-item">
                            <div class="listing-item">
                                <div class="listing-img">
                                    <a href="<?= url('/' . $pagesDetail->page_slug . '/'. $blogDetail[$b]['blog_slug']); ?>">
                                        <img src="<?= asset('/uploads/blog/'.$blogDetail[$b]['blog_image']); ?>" class="img-fluid" alt="<?= $blogDetail[$b]['blog_title']; ?>">
                                    </a>
                                </div>
                                <div class="listing-content news-content">
                                    <div class="listing-venue-owner">
                                        <div class="navigation">
                                            <i class="feather-calendar"></i> <?= date('d M, Y', strtotime($blogDetail[$b]['blog_date'])); ?>
                                        </div>
                                    </div>
                                    <h3 class="listing-title blog-title">
                                        <a href="<?= url('/' . $pagesDetail->page_slug . '/'. $blogDetail[$b]['blog_slug']); ?>"><?= $blogDetail[$b]['blog_title']; ?></a>
                                    </h3>
                                    <div class="listing-button read-new text-center">
                                        <span><a href="<?= url('/' . $pagesDetail->page_slug . '/'. $blogDetail[$b]['blog_slug']); ?>">5 Min To Read</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Blog -->
                    </div>
                <?php } }
                exit;
            } else {
                $bcategoryName = '';
                //get all blog
                $blogDetail = Blog::where('blog_status', '1')->orderBy('blog_order', 'DESC')->skip($start)->take($limit)->get()->toArray();
                $rows = Blog::where('blog_status', '1')->count();

                return view('blog')->with([
                    'pagesDetail' => $pagesDetail,
                    'bcategoryName' => $bcategoryName,
                    'bcategoryNameArray' => $bcategoryNameArray,
                    'blogDetail' => $blogDetail,
                    'pagecount' => $pagecount,
                    'rows' => $rows,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Blog page error: '.$e->getMessage());
            return redirect('/404');
        }
    }

    public function blogDetail($slug)
    {
        $bcategoryNameArray = $totalBlogArray = [];
        $pagesDetail = Pages::where('page_id', 2)->first();
        if(!$pagesDetail){
            return redirect('/404');
        }
        $bcategoryDetail = Bcategory::get(['bcategory_id', 'bcategory_title', 'bcategory_slug'])->toArray();
        for($b=0; $b < count($bcategoryDetail); $b++) {
            $bcategoryNameArray[$bcategoryDetail[$b]['bcategory_id']] = $bcategoryDetail[$b]['bcategory_title'];
        }

        $totalBlog = Blog::select('bcategory.bcategory_id', DB::raw('COUNT(blog.blog_id) AS total_blog'))
            ->join('bcategory', 'blog.bcategory_id', 'bcategory.bcategory_id')
            ->groupBy('bcategory.bcategory_id')->get()->toArray();
        for($t=0; $t < count($totalBlog); $t++) {
            $totalBlogArray[$totalBlog[$t]['bcategory_id']] = $totalBlog[$t]['total_blog'];
        }

        $blogDetail = Blog::where('blog_slug', trim($slug))->first();
        $popularDetail = Blog::where(['blog_status' => '1', 'blog_popular_status' => '1'])->orderBy('blog_order', 'DESC')->take(6)->get(['blog_title', 'blog_slug'])->toArray();
        $recentDetail = Blog::where('blog_status', '1')->where('blog_id', '<>', $blogDetail->blog_id)->orderBy('blog_order', 'DESC')->take(3)->get(['blog_title', 'blog_slug', 'blog_image'])->toArray();
        return view('blogdetail')->with([
            'pagesDetail' => $pagesDetail,
            'bcategoryNameArray' => $bcategoryNameArray,
            'totalBlogArray' => $totalBlogArray,
            'bcategoryDetail' => $bcategoryDetail,
            'blogDetail' => $blogDetail,
            'popularDetail' => $popularDetail,
            'recentDetail' => $recentDetail,
        ]);
    }

    public function membership() {
        try {
            Session::forget('discount');
            Session::forget('discount_text');
            Session::forget('discount_code');
            Session::forget('discount_id');

            $pagesDetail = Pages::where('page_id', 3)->first();
            if(!$pagesDetail){
                return redirect('/404');
            }
            $orderDetail = [];
            $membershipDetail = Membership::get()->toArray();
            if (session()->has('customer_id') && session()->has('customer_id') > 0) {
                $order = MembershipOrder::where([
                    ['customer_id', Session::get('customer_id')],
                    ['msorder_status', '1'],
                    ['msorder_end_date', '>=', Carbon::today()->toDateString()]
                ])->first();
                if ($order) {
                    $orderDetail = $order->toArray();
                }
            }
            return view('membership')->with(['pagesDetail' => $pagesDetail, 'membershipDetail' => $membershipDetail, 'orderDetail' => $orderDetail]);
        } catch (Exception $e) {
            Log::error('Catch error membership: ' . $e->getMessage());
        }
    }

    public function bookLounge(Request $request) {
        $pagesDetail = Pages::where('page_id', 2)->first();
        if(!$pagesDetail){
            return redirect('/404');
        }
        $cityNameArray = $cityIdArray = [];
        if (isset($request->city) && $request->city != "") {
            $cityDetail = Cities::where('cities_name', trim(ucwords($request->city)))->get()->toArray();
            if ($cityDetail) {
                for($c=0; $c < count($cityDetail); $c++) {
                    $cityIdArray[] = $cityDetail[$c]['cities_id'];
                }
            }
        }
        $cityDetail = Cities::get()->toArray();
        $cityNameArray = [];
        for($c=0; $c < count($cityDetail); $c++) {
            $cityNameArray[$cityDetail[$c]['cities_id']] = $cityDetail[$c]['cities_name'];
        }
        if (count($cityIdArray) > 0) {
            $loungeDetail = Lounge::where(['lounge_status' => '1'])->whereIn('cities_id', $cityIdArray)->orderBy('lounge_order')->get()->toArray();
        } else {
            $loungeDetail = Lounge::where(['lounge_status' => '1'])->orderBy('lounge_order')->get()->toArray();
        }
        return view('booklounge')->with(['pagesDetail' => $pagesDetail, 'cityNameArray' => $cityNameArray, 'loungeDetail' => $loungeDetail]);
    }

    public function bookLoungeDetail($slug) {
        $pagesDetail = Pages::where('page_id', 2)->first();
        if(!$pagesDetail){
            return redirect('/404');
        }
        $loungeDetail = Lounge::where('lounge_slug', trim($slug))->first();
        $limageDetail = LoungeImage::where('lounge_id', $loungeDetail->lounge_id)->orderBy('limage_order')->get(['limage_id', 'limage_image'])->toArray();
        $encryptedId = Crypt::encrypt($loungeDetail->lounge_id);
        $ltimeDetail = DB::select("SELECT 
                                       GROUP_CONCAT(ltime_day ORDER BY FIELD(ltime_day, 'MON','TUE','WED','THU','FRI','SAT','SUN')) AS days,
                                       time_range,
                                       rate
                                  FROM (
                                      SELECT 
                                          ltime_day,
                                          CONCAT(
                                              LPAD(ltime_open_hour, 2, '0'), ':', LPAD(ltime_open_time, 2, '0'), ' ', ltime_open_ap,
                                              ' - ',
                                              LPAD(ltime_close_hour, 2, '0'), ':', LPAD(ltime_close_time, 2, '0'), ' ', ltime_close_ap
                                          ) AS time_range,
                                          ltime_text AS rate
                                      FROM lounge_time
                                      WHERE lounge_id = '".$loungeDetail->lounge_id."'
                                   ) AS sub
                                  GROUP BY 
                                      time_range, rate
                                  ORDER BY 
                                      MIN(FIELD(ltime_day, 'MON','TUE','WED','THU','FRI','SAT','SUN')),
                                      CAST(rate AS UNSIGNED)");
        return view('bookloungedetail')->with(['pagesDetail' => $pagesDetail, 'loungeDetail' => $loungeDetail, 'limageDetail' => $limageDetail, 'ltimeDetail' => $ltimeDetail, 'loungeId' => $encryptedId]);
    }

    public function sendMail($fromEmail='', $toEmail='', $fromName='', $toName='', $subject='', $message='', $isAttachment=0, $fileName='')
    {
        $mail = new PHPMailer(true);
        try {
            $mail->AddAddress($toEmail, $toName);
            $mail->SetFrom($fromEmail, $fromName);
            $mail->Subject = $subject;
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
            if ($isAttachment > 0 && $fileName != "")
            {
                $mail->AddAttachment('/home/pvja0pu1hp5k/public_html/public/uploads/career/' . $fileName);
            }
            $mail->MsgHTML($message);
            $mail->Send();
            //print_r($mail); die;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function sendSMS($mobileNumber, $message) {
        //$authKey 		= "23a1a991572963a7d9a64c436a3dfd";
        $authKey 		= "3f357f49d352f63de49bfdf118a4458";
        $senderId 		= "YARIOK";
        $message 		= urlencode($message);

        $url  			= ("http://sms1.omnetsolution.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=$authKey&message=$message&senderId=$senderId&routeId=1&mobileNos=$mobileNumber&smsContentType=english");
        $data 			= @file_get_contents($url);

        return true;
    }

    public function error404() {
        $pagesDetail = Pages::where('page_id', 18)->first();
        if(!$pagesDetail){
            return redirect('/404');
        }
        return view('404error')->with(['pagesDetail' => $pagesDetail]);
    }

    public static function postMethod($url, $postFields) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded',
                'X-Api-Key: 09782EDC-9B15-4228-9E1D-C6E6FEC9FA97'
            ));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        } catch (Exception $e) {
            //echo "error in recording";
        }
    }
}
