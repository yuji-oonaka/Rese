document.addEventListener('DOMContentLoaded', function() {
    const contentWrapper = document.querySelector('.content-wrapper');
    const reviewModal = document.getElementById('reviewModal');
    const qrModal = document.getElementById('qrCodeModal');

    // レビューモーダル関連の機能
    window.showReviewForm = function(reservationId) {
        document.getElementById('reservationId').value = reservationId;
        showModal('reviewModal');
    }

    // QRコードモーダル関連の機能
    window.showQRCode = function(imagePath) {
        document.getElementById('qrCodeImage').src = imagePath;
        showModal('qrCodeModal');
    }

    // モーダルを表示する関数
    function showModal(modalId) {
        document.body.classList.add('modal-active');
        contentWrapper.classList.add('blur');
        document.getElementById(modalId).style.display = 'block';
    }

    // モーダルを非表示にする関数
    function hideModal(modalId) {
        document.body.classList.remove('modal-active');
        contentWrapper.classList.remove('blur');
        document.getElementById(modalId).style.display = 'none';
    }

    // 閉じるボタンのイベントリスナー
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            hideModal(this.closest('.modal').id);
        });
    });

    // 背景クリックでモーダルを閉じる
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            hideModal(event.target.id);
        }
    });

    // サクセスアラートの自動非表示
    var successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.display = 'none';
        }, 5000);
    }

    // QRコード生成機能
    window.generateQrCode = function(reservationId) {
        fetch(`/generate-qr-code/${reservationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('QRコードの生成に失敗しました。');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('QRコードの生成中にエラーが発生しました。');
        });
    }
});