<?php

namespace App\Http\Controllers;

use App\Events\sendError;
use App\Models\Messages;
use App\Models\Player;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public static function sendRead(Request $request)
    {
        if (isset($request->message_id)) {
            $user = auth()->user();
            if (isset($user)) {
                $player = Player::find($user->playerId);
                if (isset($player)) {
                    $message = Messages::where('receiverId', $player->id)->where('id', $request->message_id)->first();
                    if (isset($message)) {
                        $message->wasRead = 1;
                        $message->save();
                    }
                    $messages = [];
                    $messages['sendMessages'] = $player->sendMessages;
                    $messages['receivedMessages'] = $player->receivedMessages;
                    return ['player' => $player->getReturnValues(), "messages" => $messages];
                }
            }
        }
        return ['player' => null, "messages" => null];
    }

    public static function sendDelete(Request $request)
    {
        if (isset($request->message_id)) {
            $user = auth()->user();
            if (isset($user)) {
                $player = Player::find($user->playerId);
                if (isset($player)) {
                    $message = Messages::where('receiverId', $player->id)->where('id', $request->message_id)->first();
                    if (isset($message)) {
                        $message->delete();
                    } else {
                        $message = Messages::where('senderId', $player->id)->where('id', $request->message_id)->first();
                        $message?->delete();
                    }
                    $messages = [];
                    $messages['sendMessages'] = $player->sendMessages;
                    $messages['receivedMessages'] = $player->receivedMessages;
                    return ['player' => $player->getReturnValues(), "messages" => $messages];
                }
            }
        }
        return ['player' => null, "messages" => null];
    }

    public static function reloadMessage()
    {
        $user = auth()->user();
        if (isset($user)) {
            $player = Player::find($user->playerId);
            $messages = [];
            $messages['sendMessages'] = $player->sendMessages;
            $messages['receivedMessages'] = $player->receivedMessages;
            return ['player' => $player->getReturnValues(), "messages" => $messages];
        } else {
            event(new sendError($user, "No User"));
            return ['player' => null, "messages" => null];
        }

    }

    public static function sendMessage(Request $request)
    {
        $user = auth()->user();
        if (isset($user)) {
            $sender = Player::find($user->playerId);
        } else {
            event(new sendError($user, "No User"));
            return ['player' => null, "messages" => null];
        }
        if (isset($sender)) {
            $messageArray = $request->messageArray;
            if (isset($messageArray)) {
                $receiverName = explode("/split_/", $messageArray)[0];
                if ($receiverName === "Ludus2 Team" || $receiverName === "PvP-Info") {
                    event(new sendError($user, "You can't answer System Message"));
                    $messages = [];
                    $messages['sendMessages'] = $sender->sendMessages;
                    $messages['receivedMessages'] = $sender->receivedMessages;
                    return ['player' => $sender->getReturnValues(), "messages" => $messages];
                }
                $messageText = explode("/split_/", $messageArray)[1];
                $receiver = Player::where('name', $receiverName)->first();
                if (isset($receiver)) {
                    $message = Messages::where('senderId', $receiver->id)->where('receiverId', $sender->id)->first();
                    if (isset($message)) {
                        $message->message .= PHP_EOL . PHP_EOL . __('custom.message_answer') . ':' . PHP_EOL . $messageText;
                    } else {
                        $message = new Messages();
                        $message->message = $messageText;
                    }
                    $message->senderId = $sender->id;
                    $message->senderName = $sender->name;
                    $message->receiverId = $receiver->id;
                    $message->receiverName = $receiver->name;
                    $message->wasRead = 0;
                    $message->created_at = Carbon::now();
                    $message->save();
                } else {
                    event(new sendError($user, "No Receiver found"));
                }
            }
            $messages = [];
            $messages['sendMessages'] = $sender->sendMessages;
            $messages['receivedMessages'] = $sender->receivedMessages;
            return ['player' => $sender->getReturnValues(), "messages" => $messages];
        }
        return ['player' => null, "messages" => null];
    }
}
