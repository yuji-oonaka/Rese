document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-favorite').forEach(button => {
        // キラキラ要素を追加
        const wrapper = document.createElement('div');
        wrapper.className = 'sparkle-wrapper';
        button.parentNode.insertBefore(wrapper, button);
        wrapper.appendChild(button);

        const sparklesContainer = document.createElement('div');
        sparklesContainer.className = 'sparkles';
        wrapper.appendChild(sparklesContainer);

        button.addEventListener('click', function(e) {
            e.preventDefault();
            const shopId = this.dataset.shopId;
            const form = this.closest('form');
            const csrfToken = form.querySelector('input[name="_token"]').value;

            // キラキラエフェクトを作成
            createSparkles(sparklesContainer);

            this.classList.add('animating');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    this.classList.add('active');
                } else {
                    this.classList.remove('active');
                }

                setTimeout(() => {
                    this.classList.remove('animating');
                }, 400);
            })
            .catch(error => {
                console.error('Error:', error);
                this.classList.remove('animating');
            });
        });
    });
});

// キラキラエフェクトを生成する関数
function createSparkles(container) {
    for (let i = 0; i < 8; i++) {
        const sparkle = document.createElement('div');
        sparkle.className = 'sparkle';

        // ランダムな位置と角度を設定
        const angle = (i * 45) + Math.random() * 30;
        const distance = 20 + Math.random() * 20;
        const x = Math.cos(angle * Math.PI / 180) * distance;
        const y = Math.sin(angle * Math.PI / 180) * distance;

        sparkle.style.left = x + 'px';
        sparkle.style.top = y + 'px';
        sparkle.style.animation = `sparkle 0.8s ease-in-out forwards`;

        container.appendChild(sparkle);

        // アニメーション終了後に要素を削除
        setTimeout(() => {
            sparkle.remove();
        }, 800);
    }
}
function redirectToLogin() {
    // 現在のURLをセッションストレージに保存
    sessionStorage.setItem('returnTo', window.location.href);
    // ログインページへリダイレクト
    window.location.href = '{{ route("login") }}';
}

// お気に入りボタンのイベントハンドラ（認証済みユーザー用）
document.querySelectorAll('.favorite-form .btn-favorite').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        // 既存のお気に入り処理
        const shopId = this.dataset.shopId;
        const form = this.closest('form');
        const csrfToken = form.querySelector('input[name="_token"]').value;
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const areaSelect = document.querySelector('select[name="area"]');
    const sortSelect = document.querySelector('select[name="sort"]');
    const genreSelect = document.querySelector('select[name="genre"]');
    const searchInput = document.querySelector('#search-input');

    // フィルター適用関数
    function applyFilters() {
        let url = new URL(window.location);

        // パラメータセット
        if (sortSelect.value) {
            url.searchParams.set('sort', sortSelect.value);
        } else {
            url.searchParams.delete('sort');
        }

        if (areaSelect.value) {
            url.searchParams.set('area', areaSelect.value);
        } else {
            url.searchParams.delete('area');
        }

        if (genreSelect.value) {
            url.searchParams.set('genre', genreSelect.value);
        } else {
            url.searchParams.delete('genre');
        }

        if (searchInput.value) {
            url.searchParams.set('search', searchInput.value);
        } else {
            url.searchParams.delete('search');
        }

        // 新URLに移動
        window.location.href = url.toString();
    }

    // イベントリスナー追加
    areaSelect.addEventListener('change', applyFilters);
    sortSelect.addEventListener('change', applyFilters);
    genreSelect.addEventListener('change', applyFilters);

    // Enterキーで検索
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});
