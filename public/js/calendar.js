document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'th',
        events: '/calendar/events',
        eventLimit: true,
        dayMaxEventRows: 3,
        contentHeight: 'auto',
        aspectRatio: 2,
        height: 'auto',

        // ปรับแต่งข้อความในปฏิทิน
        eventContent: function (eventInfo) {
            var startTime = eventInfo.event.extendedProps.start_time || '';
            var endTime = eventInfo.event.extendedProps.end_time || '';
            var title = eventInfo.event.title || '';

            // แสดงเฉพาะกรณีที่มี timeslots
            var contentHtml = title;
            if (startTime && endTime) {
                contentHtml = `
                    <div style="
                        white-space: nowrap; 
                        overflow: hidden; 
                        text-overflow: ellipsis; 
                        max-width: 200px;"
                    >
                        ${startTime} น. - ${endTime} น. ${title}
                    </div>
                `;
            }

            return { html: contentHtml };
        },

        eventClick: function (info) {
            info.jsEvent.preventDefault();

            document.getElementById('eventTitle').innerText = info.event.title;

            var timeslotText = '';
            var timeslotLabel = 'รอบการเข้าชม:';
            if (info.event.extendedProps.start_time && info.event.extendedProps.end_time) {
                timeslotText = `${info.event.extendedProps.start_time} - ${info.event.extendedProps.end_time}`;
            } else {
                // ถ้าไม่มี timeslot, แสดงระยะเวลากิจกรรม
                var startDate = new Date(info.event.start);
                var endDate = new Date(info.event.end);
                var durationDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                timeslotLabel = 'ระยะเวลากิจกรรม:';
                timeslotText = `${durationDays} วัน`;
            }
        
            document.getElementById('eventTimeslotLabel').innerText = timeslotLabel;  // เปลี่ยนข้อความ label
            document.getElementById('eventTimeslot').innerText = timeslotText;
        
            var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
            myModal.show();
        },

        eventDidMount: function (info) {
            var status = info.event.extendedProps.status;
            if (status === 0) {
                info.el.style.backgroundColor = '#ffc107';
                info.el.style.color = '#ffffff';
            } else if (status === 1) {
                info.el.style.backgroundColor = '#28a745';
                info.el.style.color = '#ffffff';
            } else if (status === 2) {
                info.el.style.backgroundColor = '#dc3545';
                info.el.style.color = '#ffffff';
            }
        },
    });

    calendar.render();
});