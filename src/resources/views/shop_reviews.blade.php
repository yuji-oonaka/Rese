<div class="review-content">
    <!-- 青いヘッダー -->
    <div class="review-header">
        <h1>全ての口コミ情報</h1>
    </div>

    <!-- 口コミ一覧 -->
    @forelse($reviews as $review)
        <div class="review-item" style="margin-bottom: 32px; border-bottom: 1px solid #ddd; padding-bottom: 24px;">
            <!-- 星評価 -->
            <div class="stars" style="font-size: 1.5em; color: #0070f3; margin-bottom: 8px;">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                @endfor
            </div>
            
            <!-- レビューテキスト -->
            @if($review->comment)
                <p class="review-text" style="margin-bottom: 8px;">{{ $review->comment }}</p>
            @endif
            
            <!-- レビュー画像 -->
            @if($review->image_path)
                <div class="review-image" style="margin-bottom: 8px;">
                    <img src="{{ asset('storage/' . $review->image_path) }}" alt="レビュー画像" style="width: 160px; height: 160px; object-fit: cover;">
                </div>
            @endif

            <!-- 管理者削除ボタン -->
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <form class="admin-delete-form" 
                        action="{{ route('admin.reviews.destroy', $review) }}" 
                        method="POST"
                        style="display:inline-block; margin-top: 8px;"
                        onsubmit="return confirm('管理者権限でこの口コミを削除します。よろしいですか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-admin-delete" style="background:#ff4500; color:white; border:none; padding:8px 16px; border-radius:4px; font-size:1em; cursor:pointer;">
                            <i class="fas fa-trash-alt"></i> 管理者削除
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    @empty
        <p class="no-reviews">まだ口コミがありません。</p>
    @endforelse

    <!-- ページネーション -->
    <div class="pagination-wrapper" style="margin-top:24px;">
        {{ $reviews->links() }}
    </div>
</div>
