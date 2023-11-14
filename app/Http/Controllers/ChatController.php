<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\ChatStoreRequest;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\User\UserResource;
use App\Models\Chat;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function index(Request $request): Response
    {
        $allUsers = User::query()
            ->where('id', '!=', auth()->id())
            ->get();

        $users = UserResource::collection($allUsers)
            ->resolve();

        $chats = auth()->user()
                        ->chats()
                        ->has('messages') // find chats who have message
                        ->with(['lastMessage', 'chatWith'])
                        ->withCount('unreadableMessageStatus')
                        ->get();

        $chats = ChatResource::collection($chats)->resolve();

        return Inertia::render(
            'Chat/Index',
            compact(
                'users',
                'chats'
            ));
    }

    public function store(ChatStoreRequest $request): Application|JsonResponse|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $data = $request->validated();

        $userIds = array_merge($data['users'], (array)auth()->id());

        sort($userIds);

        $userIdsString = implode('-', $userIds);

        try {
            DB::beginTransaction();

            $chat = Chat::query()->updateOrCreate([
                'users' => $userIdsString
            ], [
                'title' => $data['title']
            ]);

            $chat->users()->sync($userIds);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

        //$chat = ChatResource::make($chat)->resolve();
        return redirect(route('chats.show', $chat->id));

    }

    public function show(Chat $chat)
    {
        $page = request('page') ?? 1;
        $users = $chat->users()->get();
        $users = UserResource::collection($users)->resolve();

        $messages = $chat->messages()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(
                5,
                '*',
                'page',
                $page);

        $chat->unreadableMessageStatus()->update([
            'is_read' => true
        ]);

        $isLastPage = (int)$page === (int)$messages->lastPage();

        $messages = MessageResource::collection($messages)->resolve();

        if($page > 1) {
            return response()->json([
                'messages' => $messages,
                'isLastPage' => $isLastPage,
            ]);
        }
        $chat = ChatResource::make($chat)->resolve();

        return Inertia::render('Chat/Show', compact(
            'chat',
            'users',
            'messages',
            'isLastPage',
        ));
    }
}
