<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Chat;
use App\Models\Setting;
use App\Models\Pages;
use Exception;
use Session;
use Crypt;

class ChatController extends Controller
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
    
    public function index(Request $request)
    {
        try {
            $pagesDetail = Pages::where('page_id', 13)->first();
            if(!$pagesDetail){
                return redirect()->route('404');
            }
                
            $userId = Session::get('customer_id');
            
            // Get all unique people the user has chatted with
            $chats = Chat::where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            $contacts = [];
            foreach ($chats as $chat) {
                $otherId = ($chat->sender_id == $userId) ? $chat->receiver_id : $chat->sender_id;
                if (!isset($contacts[$otherId])) {
                    $otherUser = ($chat->sender_id == $userId) ? $chat->receiver : $chat->sender;
                    
                    // Image logic
                    $userImage = url('/image/avatar-01.jpg');
                    if ($otherUser && !empty($otherUser->customer_image) && file_exists(public_path('uploads/customer/'.$otherUser->customer_image))) {
                        $userImage = asset('uploads/customer/'.$otherUser->customer_image);
                    }

                    $contacts[$otherId] = [
                        'user' => $otherUser,
                        'user_image' => $userImage,
                        'last_message' => $chat->message,
                        'last_message_at' => $chat->created_at,
                        'unread_count' => Chat::where('sender_id', $otherId)
                            ->where('receiver_id', $userId)
                            ->where('is_read', false)
                            ->count()
                    ];
                }
            }

            $autoOpenId = null;
            if ($request->has('receiver_id')) {
                try {
                    $autoOpenId = Crypt::decrypt($request->receiver_id);
                } catch (Exception $e) {
                    $autoOpenId = $request->receiver_id; // Fallback if not encrypted
                }
            }
            
            // If a new chat is initiated via product page, ensure the receiver is in the list
            if ($autoOpenId && $autoOpenId != $userId && !isset($contacts[$autoOpenId])) {
                $otherUser = Customer::find($autoOpenId);
                if ($otherUser) {
                    $userImage = url('/image/avatar-01.jpg');
                    if (!empty($otherUser->customer_image) && file_exists(public_path('uploads/customer/'.$otherUser->customer_image))) {
                        $userImage = asset('uploads/customer/'.$otherUser->customer_image);
                    }

                    $contacts[$autoOpenId] = [
                        'user' => $otherUser,
                        'user_image' => $userImage,
                        'last_message' => '',
                        'last_message_at' => null,
                        'unread_count' => 0
                    ];
                }
            }

            // Sort contacts by latest message
            uasort($contacts, function($a, $b) {
                if ($a['last_message_at'] == $b['last_message_at']) return 0;
                return ($a['last_message_at'] < $b['last_message_at']) ? 1 : -1;
            });

            return view('chat', compact('contacts', 'autoOpenId', 'pagesDetail'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show($otherId)
    {
        try {
            $userId = Session::get('customer_id');

            // Mark messages as read
            Chat::where('sender_id', $otherId)
                ->where('receiver_id', $userId)
                ->update(['is_read' => true]);

            $sender = Customer::find($userId);
            $senderImage = url('/image/avatar-01.jpg');
            if ($sender && !empty($sender->customer_image) && file_exists(public_path('uploads/customer/'.$sender->customer_image))) {
                $senderImage = asset('uploads/customer/'.$sender->customer_image);
            }

            $messages = Chat::where(function($q) use ($userId, $otherId) {
                    $q->where('sender_id', $userId)->where('receiver_id', $otherId);
                })->orWhere(function($q) use ($userId, $otherId) {
                    $q->where('sender_id', $otherId)->where('receiver_id', $userId);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            $receiver = Customer::find($otherId);
            if ($receiver) {
                $userImage = url('/image/avatar-01.jpg');
                if (!empty($receiver->customer_image) && file_exists(public_path('uploads/customer/'.$receiver->customer_image))) {
                    $userImage = asset('uploads/customer/'.$receiver->customer_image);
                }
                $receiver->user_image = $userImage;
            }

            return response()->json([
                'status' => 'success',
                'messages' => $messages,
                'receiver' => $receiver,
                'sender_image' => $senderImage
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $userId = Session::get('customer_id');

            $sender = Customer::find($userId);
            $senderImage = url('/image/avatar-01.jpg');
            if ($sender && !empty($sender->customer_image) && file_exists(public_path('uploads/customer/'.$sender->customer_image))) {
                $senderImage = asset('uploads/customer/'.$sender->customer_image);
            }
            
            $request->validate([
                'receiver_id' => 'required',
                'message' => 'required|string',
                'product_id' => 'nullable'
            ]);

            $receiver_id = $request->receiver_id;
            try {
                $receiver_id = Crypt::decrypt($receiver_id);
            } catch (Exception $e) {
                // Keep as is if decryption fails and it might be raw ID
            }

            $product_id = $request->product_id;
            if ($product_id) {
                try {
                    $product_id = Crypt::decrypt($product_id);
                } catch (Exception $e) {}
            }

            $chat = Chat::create([
                'sender_id' => $userId,
                'receiver_id' => $receiver_id,
                'product_id' => $product_id,
                'message' => $request->message,
                'is_read' => false
            ]);

            // Broadcast the message
            \Log::info('Triggering MessageSent event', ['chat_id' => $chat->id]);
            event(new \App\Events\MessageSent($chat, $senderImage));

            return response()->json([
                'status' => 'success',
                'chat' => $chat,
                'sender_image' => $senderImage
            ]);
        } catch (Exception $e) {
            \Log::error('Chat store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
