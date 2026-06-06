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
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Sponsor;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Service;
use App\Models\State;
use App\Models\City;
use App\Models\Favourite;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class HomeController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $settingDetail = Setting::get()->toArray();
        for ($s = 0; $s < count($settingDetail); $s++) {
            if (!defined($settingDetail[$s]['setting_name'])) {
                define($settingDetail[$s]['setting_name'], $settingDetail[$s]['setting_value']);
            }
        }
    }

    public function index()
    {
        try {
            $pagesDetail = Pages::firstWhere('page_id', 1);
            if (!$pagesDetail) {
                return redirect('/404');
            }
            $bannerDetail = Banner::where(['banner_status' => '1'])->orderBy('banner_order')->get()->toArray();
            $productDetail = Product::with(['pimages', 'city'])->where('product_status', '1')->orderBy('product_id', 'DESC')->take(8)->get()->toArray();
            $testimonialDetail = Testimonial::where('testimonial_status', 1)->orderBy('testimonial_order')->get()->toArray();
            $sponsorDetail = Sponsor::where('sponsor_status', 1)->orderBy('sponsor_order')->get()->toArray();
            $ourFeatureDetail = Service::where(['service_status' => '1', 'service_type' => '0'])->orderBy('service_order')->get()->toArray();
            $whyChooseDetail = Service::where(['service_status' => '1', 'service_type' => '1'])->orderBy('service_order')->get()->toArray();
            $blogDetail = Blog::where(['blog_status' => '1'])->orderBy('blog_order', 'DESC')->take(6)->get()->toArray();

            return view('home')->with([
                'pagesDetail' => $pagesDetail,
                'bannerDetail' => $bannerDetail,
                'productDetail' => $productDetail,
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
            $pagesDetail = Pages::findOrFail(5);
            return view('contact', compact('pagesDetail'));
        } catch (\Exception $e) {
            return redirect('/404');
        }
    }

    public function contact_insert(Request $request)
    {
        try {
            $pagesDetail = Pages::firstWhere('page_id', 5);
            if (!$pagesDetail) {
                return redirect('/404');
            }
            $lastOrder = Contact::orderBy('contact_order', 'DESC')->first();
            Contact::create([
                'contact_name' => ucwords(strtolower($_POST['fname'])) . ' ' . ucwords(strtolower($_POST['lname'])),
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

            $fromName = 'Yanzzo';
            $subjectUser = "Thank You for reaching out: Yanzzo is here to help !";
            $subjectAdmin = "New Inquiry from Yanzzo By " . ucwords(strtolower($_POST['fname'])) . ' ' . ucwords(strtolower($_POST['lname']));

            $messageHeaderUser =
                "<tr>
                        <td style='font-size:15px'>Hello " . ucwords(strtolower($_POST['fname'])) . ' ' . ucwords(strtolower($_POST['lname'])) . ",</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>Thank you for reaching out to us.<br>We appreciate your interest in Yanzzo. Our team is currently reviewing your inquiry and we will get back to you shortly.<br>If you have any urgent questions or concerns, feel free to contact us directly at <a href='tel:919509914499'>+91 950 991 4499</a>.</td>
                    </tr><br>";

            $messageHeaderAdmin =
                "<tr>
                        <td style='font-size:15px'>Dear Administrator,</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>" . ucwords(strtolower($_POST['fname'])) . ' ' . ucwords(strtolower($_POST['lname'])) . " submitted contact form from website.</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>Details are below</td>
                    </tr><br>";

            $message =
                "<tr>
                        <td style=\"font-size:15px; background:#dbeef4;\">
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td width=\"150\"><strong>Full Name: </strong></td>
                                <!--<td>&nbsp;</td>-->
                                <td align=\"left\" valign=\"top\">" . ucwords(strtolower($_POST['fname'])) . ' ' . ucwords(strtolower($_POST['lname'])) . "</td>
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
                                <td align=\"left\" valign=\"top\">" . $_POST['mobile'] . "</td>
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
                                <td align=\"left\" valign=\"top\">" . $_POST['email'] . "</td>
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
                                <td align=\"left\" valign=\"top\">" . $_POST['message'] . "</td>
                            </tr>
                            </table>
                        </td>
                    </tr>";

            $messageFooterUser =
                "<br><tr>
                        <td style='font-size:15px'>Thank you,</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>Yanzzo Team.</td>
                    </tr>";
            $messageFooterAdmin =
                "<br><br><tr>
                        <td style='font-size:15px'>Thank you,</td>
                    </tr>
                    <tr>
                        <td style='font-size:15px'>Yanzzo Team.</td>
                    </tr>";

            //mail sent to user
            $this->sendMail(FROM_EMAIL, $_POST['email'], $fromName, ucwords(strtolower($_POST['fname'])) .' '. ucwords(strtolower($_POST['lname'])), $subjectUser, $messageHeaderUser . $message . $messageFooterUser);

            //mail sent to admin
            if (ADMIN_EMAIL != "") {
                $this->sendMail(FROM_EMAIL, ADMIN_EMAIL, $fromName, '', $subjectAdmin, $messageHeaderAdmin . $message . $messageFooterAdmin);
            }
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

    public function blog(Request $request)
    {
        try {
            //print_r($request->all()); die;
            //Start Pagination
            $pageno = 1;
            $limit = 9;
            $start = 0;
            if (isset($request['page']) && $request['page'] != '') {
                $pageno = $request['page'];
            }
            $start = ($pageno - 1) * $limit;
            $pagecount = $limit * $pageno;
            //End Pagination

            $bcategoryNameArray = [];
            $pagesDetail = Pages::firstWhere('page_id', 4);
            if (!$pagesDetail) {
                return redirect('/404');
            }
            $bcategoryDetail = Bcategory::get(['bcategory_id', 'bcategory_title'])->toArray();
            for ($b = 0; $b < count($bcategoryDetail); $b++) {
                $bcategoryNameArray[$bcategoryDetail[$b]['bcategory_id']] = $bcategoryDetail[$b]['bcategory_title'];
            }
            //get category wise blog
            if (isset($request['act']) && $request['act'] == 'load_blog') {
                //get all blog
                $blogDetail = Blog::where('blog_status', '1')->orderBy('blog_order', 'DESC')->skip($start)->take($limit)->get()->toArray();
                if (is_array($blogDetail) && count($blogDetail) > 0) {
                    for ($b = 0; $b < count($blogDetail); $b++) { ?>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <!-- Blog -->
                            <div class="featured-venues-item">
                                <div class="listing-item">
                                    <div class="listing-img">
                                        <a href="<?= url('/' . $pagesDetail->page_slug . '/' . $blogDetail[$b]['blog_slug']); ?>">
                                            <img src="<?= asset('/uploads/blog/' . $blogDetail[$b]['blog_image']); ?>" class="img-fluid"
                                                alt="<?= $blogDetail[$b]['blog_title']; ?>">
                                        </a>
                                    </div>
                                    <div class="listing-content news-content">
                                        <div class="listing-venue-owner">
                                            <div class="navigation">
                                                <i class="feather-calendar"></i> <?= date('d M, Y', strtotime($blogDetail[$b]['blog_date'])); ?>
                                            </div>
                                        </div>
                                        <h3 class="listing-title blog-title">
                                            <a
                                                href="<?= url('/' . $pagesDetail->page_slug . '/' . $blogDetail[$b]['blog_slug']); ?>"><?= $blogDetail[$b]['blog_title']; ?></a>
                                        </h3>
                                        <div class="listing-button read-new text-center">
                                            <span><a href="<?= url('/' . $pagesDetail->page_slug . '/' . $blogDetail[$b]['blog_slug']); ?>">5 Min
                                                    To Read</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Blog -->
                        </div>
                    <?php }
                }
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
            Log::error('Blog page error: ' . $e->getMessage());
            return redirect('/404');
        }
    }

    public function blogDetail($slug)
    {
        $bcategoryNameArray = $totalBlogArray = [];
        $pagesDetail = Pages::firstWhere('page_id', 4);
        if (!$pagesDetail) {
            return redirect('/404');
        }
        $bcategoryDetail = Bcategory::get(['bcategory_id', 'bcategory_title', 'bcategory_slug'])->toArray();
        for ($b = 0; $b < count($bcategoryDetail); $b++) {
            $bcategoryNameArray[$bcategoryDetail[$b]['bcategory_id']] = $bcategoryDetail[$b]['bcategory_title'];
        }

        $totalBlog = Blog::select('bcategory.bcategory_id', DB::raw('COUNT(blog.blog_id) AS total_blog'))
            ->join('bcategory', 'blog.bcategory_id', 'bcategory.bcategory_id')
            ->groupBy('bcategory.bcategory_id')->get()->toArray();
        for ($t = 0; $t < count($totalBlog); $t++) {
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

    public function product(Request $request)
    {
        try {
            $pagesDetail = Pages::firstWhere('page_id', 3);
            if (!$pagesDetail) {
                return redirect('/404');
            }

            // Get selected slugs from URL
            $categorySlug = $request->category;
            $subcategorySlug = $request->subcategory;

            // Fetch active products with pagination
            $query = Product::with(['pimages', 'city', 'state'])->where('product_status', '1');

            // Apply category filter if slug exists
            if (!empty($categorySlug)) {
                $categoryId = Category::where('category_slug', trim($categorySlug))->value('category_id');
                if (!$categoryId) {
                    return redirect('/404');
                }
                $query->where('category_id', $categoryId);
            }
            if (!empty($subcategorySlug)) {
                $subcategoryId = Category::where('category_slug', trim($subcategorySlug))->value('category_id');
                if (!$subcategoryId) {
                    return redirect('/404');
                }
                $query->where('subcategory_id', $subcategoryId);
            }

            // Apply keyword search
            $keyword = $request->q;
            if (!empty($keyword)) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('product_title', 'like', '%' . $keyword . '%')
                      ->orWhere('product_model', 'like', '%' . $keyword . '%')
                      ->orWhere('product_short_desc', 'like', '%' . $keyword . '%')
                      ->orWhere('product_desc', 'like', '%' . $keyword . '%')
                      ->orWhere('product_meta_keyword', 'like', '%' . $keyword . '%')
                      ->orWhereHas('city', function($q2) use ($keyword) {
                          $q2->where('city_name', 'like', '%' . $keyword . '%');
                      })
                      ->orWhereHas('state', function($q3) use ($keyword) {
                          $q3->where('state_name', 'like', '%' . $keyword . '%');
                      })
                      ->orWhereHas('category', function($q4) use ($keyword) {
                          $q4->where('category_title', 'like', '%' . $keyword . '%');
                      })
                      ->orWhereHas('subCategory', function($q5) use ($keyword) {
                          $q5->where('category_title', 'like', '%' . $keyword . '%');
                      });
                });
            }

            // Apply state filter if location exists in query
            $locationId = $request->location;
            if (!empty($locationId)) {
                $locations = is_array($locationId) ? $locationId : explode(',', $locationId);
                $query->whereIn('state_id', $locations);
            }

            // Apply city filter if city exists in query
            $cityId = $request->city;
            if (!empty($cityId)) {
                $query->where('city_id', $cityId);
            }

            // Sorting Logic
            $sortBy = $request->sort ?? 'newest';
            if ($sortBy == 'price-low') {
                $query->orderBy('product_price', 'ASC');
            } elseif ($sortBy == 'price-high') {
                $query->orderBy('product_price', 'DESC');
            } else {
                $query->orderBy('product_id', 'DESC');
            }

            $productDetail = $query
                ->paginate(9);

            // Ensure pagination links maintain params
            $productDetail->appends(array_filter([
                'category' => $categorySlug,
                'subcategory' => $subcategorySlug,
                'location' => $locationId,
                'city' => $cityId,
                'sort' => $sortBy,
                'q' => $keyword,
            ]));

            $categoryDetail = Category::where('category_parent', 0)
                ->where('category_status', '1')
                ->with([
                    'subCategory' => function ($q) {
                        $q->where('category_status', '1')
                            ->withCount([
                                'product' => function ($q2) {
                                    $q2->where('product_status', '1');
                                }
                            ])
                            ->having('product_count', '>', 0)
                            ->orderBy('category_order');
                    }
                ])
                ->orderBy('category_order')
                ->get();

            $subcategoryDetail = Category::where('category_parent', '>', 0)
                ->withCount([
                    'product' => function ($q) {
                        $q->where('product_status', '1');
                    }
                ])
                ->having('product_count', '>', 0)
                ->get();

            $stateDetail = State::where('state_status', '1')
                ->withCount([
                    'product' => function ($q) {
                        $q->where('product_status', '1');
                    }
                ])
                ->having('product_count', '>', 0)
                ->with([
                    'cities' => function ($q) {
                        $q->where('city_status', '1')
                            ->withCount([
                                'product' => function ($q2) {
                                    $q2->where('product_status', '1');
                                }
                            ])
                            ->having('product_count', '>', 0)
                            ->orderBy('city_name');
                    }
                ])
                ->orderBy('state_name')
                ->get();

            return view('product', compact(
                'pagesDetail',
                'productDetail',
                'categoryDetail',
                'subcategoryDetail',
                'categorySlug',
                'subcategorySlug',
                'stateDetail',
                'locationId',
                'cityId',
                'sortBy'
            ));
        } catch (\Exception $e) {
            Log::error('Machines page error: ' . $e->getMessage());
            return redirect('/404');
        }
    }

    public function productDetail($slug)
    {
        try {
            $pagesDetail = Pages::firstWhere('page_id', 3);
            if (!$pagesDetail) {
                return redirect('/404');
            }

            $productDetail = Product::with(['category', 'subCategory', 'customer', 'pimages', 'city'])->where('product_slug', trim($slug))->first();
            if (!$productDetail) {
                return redirect('/404');
            }

            // Increment product view count
            $productDetail->increment('product_view');

            $similarDetail = Product::with(['pimages', 'city'])->where('product_status', '1')
                ->where('product_id', '<>', $productDetail->product_id)
                ->where(function ($query) use ($productDetail) {
                    $query->where('category_id', $productDetail->category_id)->where('subcategory_id', $productDetail->subcategory_id);
                })
                ->inRandomOrder()
                ->take(3)
                ->get();

            $isFavourite = false;
            if (Session::has('customer_id')) {
                $isFavourite = Favourite::where('customer_id', Session::get('customer_id'))
                    ->where('product_id', $productDetail->product_id)
                    ->exists();
            }

            return view('productDetail')->with([
                'pagesDetail' => $pagesDetail,
                'productDetail' => $productDetail,
                'similarDetail' => $similarDetail,
                'isFavourite' => $isFavourite
            ]);
        } catch (\Exception $e) {
            Log::error('Machines page error: ' . $e->getMessage());
            return redirect('/404');
        }
    }

    public function searchSuggestions(Request $request)
    {
        $keyword = trim($request->get('q', ''));
        if (empty($keyword) || strlen($keyword) < 2) {
            return response()->json([]);
        }

        $suggestions = [];

        // Products
        $products = Product::where('product_status', '1')
            ->where('product_title', 'like', '%' . $keyword . '%')
            ->select('product_title as text')
            ->limit(5)
            ->get();

        foreach ($products as $item) {
            $suggestions[] = [
                'type' => 'Product',
                'text' => $item->text,
            ];
        }

        // Subcategories
        $subcategories = Category::where('category_status', '1')
            ->where('category_parent', '>', 0)
            ->where('category_title', 'like', '%' . $keyword . '%')
            ->select('category_title as text')
            ->limit(5)
            ->get();

        foreach ($subcategories as $item) {
            $suggestions[] = [
                'type' => 'Subcategory',
                'text' => $item->text,
            ];
        }

        // Return maximum 5 suggestions total
        return response()->json(array_slice($suggestions, 0, 5));
    }

    public function sendMail($fromEmail = '', $toEmail = '', $fromName = '', $toName = '', $subject = '', $message = '', $isAttachment = 0, $fileName = '')
    {
        $mail = new PHPMailer(true);
        try {
            $mail->AddAddress($toEmail, $toName);
            $mail->SetFrom($fromEmail, $fromName);
            $mail->Subject = $subject;
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
            if ($isAttachment > 0 && $fileName != "") {
                $mail->AddAttachment('/home/pvja0pu1hp5k/public_html/public/uploads/career/' . $fileName);
            }
            $mail->MsgHTML($message);
            $mail->Send();
            //print_r($mail); die;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function error404()
    {
        $pagesDetail = Pages::where('page_id', 18)->first();
        if (!$pagesDetail) {
            return redirect('/404');
        }
        return view('404error')->with(['pagesDetail' => $pagesDetail]);
    }

    public static function postMethod($url, $postFields)
    {
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
