document.addEventListener('DOMContentLoaded', function() {
    // 認証済みユーザー用: .favorite-form .btn-favorite のみイベントを付与
    document.querySelectorAll('.favorite-form .btn-favorite').forEach(button => {
        // キラキラエフェクトのラップ
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

            // キラキラエフェクト
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

// キラキラエフェクト生成
function createSparkles(container) {
    for (let i = 0; i < 8; i++) {
        const sparkle = document.createElement('div');
        sparkle.className = 'sparkle';
        const angle = (i * 45) + Math.random() * 30;
        const distance = 20 + Math.random() * 20;
        const x = Math.cos(angle * Math.PI / 180) * distance;
        const y = Math.sin(angle * Math.PI / 180) * distance;
        sparkle.style.left = x + 'px';
        sparkle.style.top = y + 'px';
        sparkle.style.animation = `sparkle 0.8s ease-in-out forwards`;
        container.appendChild(sparkle);
        setTimeout(() => {
            sparkle.remove();
        }, 800);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // 既存のコード
    
    // 並び替えセレクトの表示を調整する
    const sortFilter = document.getElementById('sort-filter');
    
    // 初期表示の設定
    updateSortDisplay();
    
    // 選択変更時の処理
    sortFilter.addEventListener('change', function() {
        updateSortDisplay();
        
        // ランダム選択時にタイムスタンプを追加（既存コードと同じ）
        if (this.value === 'random') {
            const timestamp = new Date().getTime();
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'random_seed';
            input.value = timestamp;
            const form = document.getElementById('search-form');
            form.appendChild(input);
        }
    });
    
    // 選択表示を更新する関数
    function updateSortDisplay() {
        const selectedOption = sortFilter.options[sortFilter.selectedIndex];
        if (selectedOption.value) {
            const selectedText = selectedOption.text;
            const displayText = '並び替え：' + selectedText;
            
            // 選択されたオプションのテキストを「並び替え：選択値」の形式に変更
            const tempOption = document.createElement('option');
            tempOption.value = selectedOption.value;
            tempOption.text = displayText;
            tempOption.selected = true;
            
            // ドロップダウンが開いていない時のみ表示を変更
            if (!sortFilter.classList.contains('active')) {
                selectedOption.text = selectedText; // 元のテキストに戻す
                sortFilter.add(tempOption, 0);
                sortFilter.remove(sortFilter.selectedIndex + 1);
            }
        }
    }
    
    // ドロップダウンの開閉状態を追跡
    sortFilter.addEventListener('focus', function() {
        this.classList.add('active');
        resetOptionTexts();
    });
    
    sortFilter.addEventListener('blur', function() {
        this.classList.remove('active');
        setTimeout(updateSortDisplay, 100);
    });
    
    // オプションテキストをリセットする関数
    function resetOptionTexts() {
        const options = sortFilter.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === 'random') options[i].text = 'ランダム';
            else if (options[i].value === 'rating_high') options[i].text = '評価が高い順';
            else if (options[i].value === 'rating_low') options[i].text = '評価が低い順';
        }
    }
});
