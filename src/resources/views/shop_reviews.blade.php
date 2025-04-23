<div class="review-content">
    <!-- 青いヘッダー -->
    <div class="review-header">
        <h1>全ての口コミ情報</h1>
    </div>
    <!-- 口コミ編集・削除ボタン -->
    <div class="review-actions">
        @auth
            @php $userReview = $reviews->where('user_id', auth()->id())->first(); @endphp
            @if($userReview)
                <span class="review-action-links">
                    <a href="{{ route('review.edit', ['reservation_id' => $userReview->reservation_id]) }}">口コミを編集</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-review-form').submit();">口コミを削除</a>
                    <form id="delete-review-form" action="{{ route('review.destroy', ['reservation_id' => $userReview->reservation_id]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </span>
            @endif
        @endauth
    </div>
    <!-- 口コミ一覧 -->
    @forelse($reviews as $review)
        <div class="review-item">
            <!-- 星評価 -->
            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                @endfor
            </div>
            <!-- レビューテキスト -->
            @if($review->comment)
                <p class="review-text">{{ $review->comment }}</p>
            @endif
            <!-- レビュー画像 -->
            @if($review->image_path)
                <div class="review-image">
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
</div>
