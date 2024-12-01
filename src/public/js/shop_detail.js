document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById('date-input');
    const timeInput = document.getElementById('time-input');
    const peopleInput = document.getElementById('people-input');

    // 今日の日付を取得
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);

    // 初期値をサマリーに反映
    if (dateInput.value) updateSummary('date', dateInput.value);
    if (timeInput.value) updateSummary('time', timeInput.value);
    if (peopleInput.value) updateSummary('people', peopleInput.value);

    // 時刻の制御を行う関数
    function updateTimeRestrictions() {
        const selectedDate = new Date(dateInput.value);
        const now = new Date();

        // 選択された日付が今日の場合
        if (selectedDate.toDateString() === now.toDateString()) {
            const hours = now.getHours();
            const minutes = now.getMinutes();

            // 現在時刻を "HH:MM" 形式に変換
            const currentTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

            // 最小時刻を設定
            timeInput.min = currentTime;

            // 既に選択されている時刻が現在時刻より前の場合、現在時刻にリセット
            if (timeInput.value && timeInput.value < currentTime) {
                timeInput.value = currentTime;
                updateSummary('time', currentTime);
            }
        } else {
            // 今日以外の日付の場合、時刻の制限を解除
            timeInput.removeAttribute('min');
        }
    }

    // イベントリスナーの設定
    dateInput.addEventListener('input', function() {
        updateSummary('date', this.value);
        updateTimeRestrictions();
    });

    timeInput.addEventListener('input', function() {
        updateSummary('time', this.value);
        updateTimeRestrictions();
    });

    peopleInput.addEventListener('change', function() {
        updateSummary('people', this.value );
    });

    // ページ読み込み時に初期チェック
    updateTimeRestrictions();

    // サマリー更新関数
    function updateSummary(type, value) {
        const element = document.getElementById(`summary-${type}`);
        if (element) {
            element.textContent = value;
        }
    }
});