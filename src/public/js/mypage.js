// public/js/mypage.js

document.addEventListener('DOMContentLoaded', function() {
    // フォーム表示・非表示の関数
    window.toggleForm = function(index) {
        var form = document.getElementById('edit-form-' + index);
        if (form.style.display === "none") {
            form.style.display = "block";
            setDateAndTimeValidation(index);
        } else {
            form.style.display = "none";
        }
    }

    // サクセスメッセージを自動的に消す機能
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            successAlert.style.transition = 'opacity 0.5s ease';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 500);
        }, 5000);
    }

    // 日付と時間のバリデーション設定
    function setDateAndTimeValidation(index) {
        const dateInput = document.getElementById('date-input-' + index);
        const timeInput = document.getElementById('time-input-' + index);

        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const currentDate = new Date();

            if (selectedDate.toDateString() === currentDate.toDateString()) {
                const hours = currentDate.getHours();
                const minutes = currentDate.getMinutes();
                const minTime = `${hours}:${minutes < 10 ? '0' + minutes : minutes}`;

                timeInput.min = minTime;
                if (timeInput.value && timeInput.value < minTime) {
                    timeInput.value = minTime;
                }
            } else {
                timeInput.removeAttribute('min');
            }
        });

        dateInput.dispatchEvent(new Event('change'));
    }

    // QRコードモーダル関連の機能
    const modal = document.getElementById('qrCodeModal');
    const closeBtn = document.querySelector('.close');

    // QRコードを表示する関数
    window.showQRCode = function(imagePath) {
        const img = document.getElementById('qrCodeImage');
        img.src = imagePath;
        modal.style.display = "block";
    };

    // ×ボタンでモーダルを閉じる
    closeBtn.addEventListener('click', function() {
        modal.style.display = "none";
    });

    // モーダルの背景クリックで閉じる
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // お気に入りボタンの機能
    document.querySelectorAll('.heart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const shopId = this.dataset.shopId;
            const form = this.closest('form');
            const csrfToken = form.querySelector('input[name="_token"]').value;
            const favoriteCard = document.getElementById(`favorite-${shopId}`);

            // お気に入り状態を切り替える
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
                if (data.success) {
                    // アニメーションを追加してカードを削除
                    favoriteCard.style.transition = 'opacity 0.3s ease';
                    favoriteCard.style.opacity = '0';

                    setTimeout(() => {
                        favoriteCard.remove();

                        // カードがすべて無くなった場合のメッセージを表示
                        const remainingCards = document.querySelectorAll('.favorite-card');
                        if (remainingCards.length === 0) {
                            const grid = document.querySelector('.favorite-grid');
                            grid.innerHTML = '<p>現在、お気に入りの店舗はありません。</p>';
                        }
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});