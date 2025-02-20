flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        allowInput: true,
        onReady: function(selectedDates, dateStr, instance) {
            instance.altInput.style.width = "230px";
            instance.altInput.style.padding = "6px";
            instance.altInput.style.border = "1px solid #ccc";
            instance.altInput.style.borderRadius = "4px";
            instance.altInput.style.textAlign = "center";
        }
 });


 document.addEventListener('DOMContentLoaded', function () {
    let dateRangeInput = document.getElementById('date_range');
    let dateRangeFields = document.getElementById('dateRangeFields');
    let toggleButton = document.getElementById('toggleDateRange');

    // เปิด-ปิดฟิลด์ช่วงวันที่
    if (localStorage.getItem('dateRangeVisible') === 'true') {
        dateRangeFields.style.display = "flex";
    }

    toggleButton.addEventListener('click', function () {
        if (dateRangeFields.style.display === "none" || dateRangeFields.style.display === "") {
            dateRangeFields.style.display = "flex";
            localStorage.setItem('dateRangeVisible', 'true');
        } else {
            dateRangeFields.style.display = "none";
            localStorage.setItem('dateRangeVisible', 'false');
        }
    });

    // ล้างค่า date_range เมื่อกดปุ่มรายวัน, เดือนนี้, ปีงบประมาณ
    document.querySelectorAll('button[name=daily], button[name=monthly], button[name=fiscal_year]').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            dateRangeInput.value = ''; // ล้างค่า
            localStorage.removeItem('dateRangeVisible'); // ปิดช่วงวันที่
            dateRangeFields.style.display = "none"; // ซ่อนช่วงวันที่

            let form = this.closest('form');
            let url = new URL(form.action, window.location.origin);

            url.searchParams.set(this.name, this.value);
            url.searchParams.delete('date_range'); // ลบค่าจาก URL

            window.location.href = url.toString();
        });
    });
});
