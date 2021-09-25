<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Http\Requests\StorePhoto;
use App\Models\Photo;
use App\UseCases\PhotoAddCommentUseCase;
use App\UseCases\PhotoDownloadUseCase;
use App\UseCases\PhotoPostingUseCase;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    /**
     * 写真一覧
     */
    public function index()
    {
        $photos = Photo::with(['user'])
            ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        return $photos;
    }

    /**
     * 写真投稿
     * @param StorePhoto $request
     * @param PhotoPostingUseCase $usecase
     * @return \Illuminate\Http\Request
     */
    public function create(StorePhoto $request, PhotoPostingUseCase $usecase)
    {
        $uploaded_file = $request->file('photo');
        $add_photo_id = $usecase->execute($uploaded_file);
        return response(['add_photo_id' => $add_photo_id], 201);
    }

    /**
     * 写真ダウンロード
     * @param String $photo
     * @return \Illuminate\Http\Response
     */
    public function download(String $photo_id, PhotoDownloadUseCase $usecase)
    {
        $download_file = $usecase->execute($photo_id);
        // 写真の存在チェック
        if (is_null($download_file)) {
            abort(404);
        }

        $disposition = 'attachment; filename="' . $download_file['file_name'] . '"';
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => $disposition,
        ];

        return response($download_file['file'], 200, $headers);
    }

    public function show(String $photo_id)
    {
        return Photo::where('id', $photo_id)->with(['user', 'comments.user'])->firstOrFail();
    }

    public function addComment(String $photo_id, StoreComment $request, PhotoAddCommentUseCase $usecase)
    {
        return response($usecase->execute(Auth::id(), $photo_id, $request->input('content')), 201);
    }
}
