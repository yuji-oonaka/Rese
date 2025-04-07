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
                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                @endfor
            </div>
            
            <!-- レビューテキスト -->
            <p class="review-text">{{ $review->comment }}</p>
            
            <!-- レビュー画像 -->
            @if($review->image_path)
                <div class="review-image">
                    <img src="{{ asset('storage/' . $review->image_path) }}" alt="レビュー画像">
                </div>
            @endif
        </div>
    @empty
        <p class="no-reviews">まだ口コミがありません。</p>
    @endforelse
    
    <!-- ページネーション -->
    <div class="pagination-wrapper">
        {{ $reviews->links() }}
    </div>
</div>
