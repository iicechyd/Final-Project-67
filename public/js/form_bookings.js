function fetchActivityPrice() {
    const activityId = document.getElementById("fk_activity_id").value;
    if (activityId) {
        fetch(`/getActivityPrice/${activityId}`)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("children_price").value =
                    data.children_price;
                document.getElementById("student_price").value =
                    data.student_price;
                document.getElementById("adult_price").value = 
                    data.adult_price;
                document.getElementById("disabled_price").value = 
                    data.disabled_price;
                document.getElementById("elderly_price").value = 
                    data.elderly_price;
                document.getElementById("monk_price").value = 
                    data.monk_price;
                calculateTotal();
            })
            .catch((error) =>
                console.error("Error fetching activity price:", error)
            );
    }
}

function toggleInput(inputId) {
    const inputField = document.getElementById(inputId);
    inputField.disabled = !inputField.disabled;
    if (inputField.disabled) {
        inputField.value = "";
    }
    calculateTotal();
}
function calculateTotal() {
    const childrenQty = document.getElementById("childrenInput").value || 0;
    const studentsQty = document.getElementById("studentInput").value || 0;
    const adultsQty = document.getElementById("adultsInput").value || 0;
    const disabledQty = document.getElementById("disabledInput").value || 0;
    const elderlyQty = document.getElementById("elderlyInput").value || 0;
    const monkQty = document.getElementById("monkInput").value || 0;

    const childrenPrice =
        parseFloat(document.getElementById("children_price").value) || 0;
    const studentPrice =
        parseFloat(document.getElementById("student_price").value) || 0;
    const adultPrice =
        parseFloat(document.getElementById("adult_price").value) || 0;
    const disabledPrice =
        parseFloat(document.getElementById("disabled_price").value) || 0;
    const elderlyPrice =
        parseFloat(document.getElementById("elderly_price").value) || 0;
    const monkPrice =
        parseFloat(document.getElementById("monk_price").value) || 0;

    const totalVisitors =
        parseInt(childrenQty) + 
        parseInt(studentsQty) + 
        parseInt(adultsQty) + 
        parseInt(disabledQty) + 
        parseInt(elderlyQty) + 
        parseInt(monkQty);
    const totalPrice =
        childrenQty * childrenPrice +
        studentsQty * studentPrice +
        adultsQty * adultPrice +
        disabledQty * disabledPrice +
        elderlyQty * elderlyPrice +
        monkQty * monkPrice;

    document.getElementById("totalVisitors").innerText = totalVisitors;
    document.getElementById("totalPrice").innerText = totalPrice.toFixed(2);
}

function fetchTimeslots() {
    const activityId = document.getElementById("fk_activity_id").value;
    
    fetch(`/timeslots/${activityId}`)
        .then((response) => response.json())
        .then((data) => {
            const timeslotSelect = document.getElementById("fk_timeslots_id");
            timeslotSelect.innerHTML =
                '<option value="">เลือกรอบการเข้าชม</option>';
            data.forEach((timeslot) => {
                const option = document.createElement("option");
                option.value = timeslot.id;
                option.text = `${timeslot.start_time} - ${timeslot.end_time}`;
                timeslotSelect.appendChild(option);
            });
        });
}
$.Thailand({
    $district: $('#subdistrict'),
    $amphoe: $('#district'),
    $province: $('#province'),
    $zipcode: $('#zipcode'),
    onLoad: function () {
        $('.tt-menu').addClass('dropdown-scrollable');
    }
});

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("instituteName").value = "โรงเรียนเด่นดี";
    document.getElementById("instituteAddress").value =
        "123/45 หมู่ 2 บ้านหมีน้อย";
    document.getElementById("visitorName").value = "นางสาวแก้วกานต์ แก่นดี";
    document.getElementById("visitorEmail").value = "kaewkarn@gmail.com";
    document.getElementById("tel").value = "0812345678";
    calculateTotal();
});

flatpickr("#booking_date", {
    dateFormat: "d/m/Y",
    minDate: new Date().fp_incr(3),
});

function confirmSubmission() {
    const childrenQty = document.getElementById("childrenInput").value || 0;
    const studentsQty = document.getElementById("studentInput").value || 0;
    const adultsQty = document.getElementById("adultsInput").value || 0;
    const disabledQty = document.getElementById("disabledInput").value || 0;
    const elderlyQty = document.getElementById("elderlyInput").value || 0;
    const monkQty = document.getElementById("monkInput").value || 0;

    // ตรวจสอบว่าผู้ใช้ไม่ได้กรอกข้อมูลในฟิลด์ใด ๆ
    if (
        childrenQty == 0 &&
        studentsQty == 0 &&
        adultsQty == 0 &&
        disabledQty == 0 &&
        elderlyQty == 0 &&
        monkQty == 0
    ) {
        // แสดงข้อความแจ้งเตือนเมื่อไม่ได้กรอกจำนวน
        document.getElementById("errorMessage").innerText = "*กรุณาระบุจำนวนผู้เข้าชมอย่างน้อย 1 ประเภท";
        document.getElementById("errorMessage").style.display = "block";
        return; // หยุดการทำงานถ้ายังไม่ได้กรอกข้อมูล
    } else {
        // ซ่อนข้อความแจ้งเตือนเมื่อกรอกข้อมูลครบ
        document.getElementById("errorMessage").style.display = "none";
    }
    const isConfirmed = confirm("คุณต้องการยืนยันการส่งข้อมูลใช่หรือไม่?");
    if (isConfirmed) {
        document.querySelector('form').submit();
    }
}
