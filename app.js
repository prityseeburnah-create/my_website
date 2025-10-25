document.addEventListener('DOMContentLoaded', () => {
  const API = "api.php";
  let revenueChart, calendar;

  // Sidebar navigation
  document.querySelectorAll('.sidebar button').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelector('.sidebar button.active')?.classList.remove('active');
      btn.classList.add('active');
      loadPage(btn.textContent.trim());
      document.querySelector('.topbar h1').textContent = btn.textContent.replace(/[^a-zA-Z ]/g,"");
    });
  });

  // Page loaders
  function loadPage(page) {
    const main = document.querySelector('.main');
    if (page.includes("Dashboard")) {
      main.innerHTML = dashboardHTML;
      loadDashboard();
      renderCalendar("#calendar");
    } else if (page.includes("Bookings")) {
      main.innerHTML = bookingsHTML;
      renderCalendar("#calendar");
    } else {
      main.innerHTML = `<section class="panel"><h2>${page}</h2><p>Content coming soon...</p></section>`;
    }
  }

  // Dashboard template
  const dashboardHTML = `
    <section class="kpis">
      <div class="card dark"><p>Total Bookings Today</p><h2 id="kpi-today">0</h2></div>
      <div class="card"><p>Upcoming Appointments</p><h2 id="kpi-upcoming">0</h2></div>
      <div class="card"><p>Most Popular Color</p><h2 id="kpi-popular">-</h2></div>
      <div class="card"><p>Monthly Revenue</p><h2 id="kpi-revenue">$0</h2></div>
    </section>
    <div class="grid">
      <section class="panel"><h3>Revenue Overview</h3><canvas id="revenueChart"></canvas></section>
      <section class="panel"><h3>Upcoming Bookings</h3><div id="calendar"></div></section>
    </div>
  `;

  // Bookings template
  const bookingsHTML = `
    <section class="panel"><h3>Manage Bookings</h3><div id="calendar"></div></section>
    <div id="modal" class="modal hidden">
      <div class="modal-content">
        <h3 id="modal-title">New Booking</h3>
        <input type="text" id="name" placeholder="Customer Name">
        <input type="text" id="subject" placeholder="Subject">
        <input type="date" id="date">
        <input type="time" id="time">
        <div class="modal-actions">
          <button id="save">💾 Save</button>
          <button id="delete" class="danger hidden">🗑️ Delete</button>
          <button id="close">❌ Close</button>
        </div>
      </div>
    </div>
  `;

  // Load Dashboard KPIs
  async function loadDashboard() {
    const res = await fetch(API + "?action=dashboard");
    if (!res.ok) return;
    const data = await res.json();
    document.getElementById("kpi-today").textContent = data.todayBookings;
    document.getElementById("kpi-upcoming").textContent = data.upcomingAppointments;
    document.getElementById("kpi-popular").textContent = data.popularColor;
    document.getElementById("kpi-revenue").textContent = "$" + data.revenue.reduce((a,b)=>a+parseFloat(b.revenue||0),0);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    if (revenueChart) revenueChart.destroy();
    revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.revenue.map(r => r.month),
        datasets: [{ label: 'Revenue', data: data.revenue.map(r => r.revenue), borderColor: '#e53935', backgroundColor: 'rgba(229,57,53,0.2)', fill: true }]
      }
    });
  }

  // Calendar renderer
  function renderCalendar(selector) {
    const el = document.querySelector(selector);
    if (!el) return;
    calendar = new FullCalendar.Calendar(el, {
      initialView: 'dayGridMonth',
      selectable: true,
      events: API + "?action=appointments",
      dateClick(info) { openModal({ date: info.dateStr }); },
      eventClick(info) {
        openModal({
          id: info.event.id,
          name: info.event.title.split(" — ")[1],
          subject: info.event.title.split(" — ")[0],
          date: info.event.startStr.split("T")[0],
          time: info.event.startStr.split("T")[1].substring(0,5)
        });
      }
    });
    calendar.render();
  }

  // Modal
  function openModal(data) {
    const modal = document.getElementById("modal");
    modal.classList.remove("hidden");
    document.getElementById("modal-title").textContent = data.id ? "Edit Booking" : "New Booking";
    document.getElementById("name").value = data.name || "";
    document.getElementById("subject").value = data.subject || "";
    document.getElementById("date").value = data.date || "";
    document.getElementById("time").value = data.time || "";
    document.getElementById("delete").classList.toggle("hidden", !data.id);

    document.getElementById("save").onclick = async () => {
      const payload = {
        id: data.id,
        name: document.getElementById("name").value,
        subject: document.getElementById("subject").value,
        date: document.getElementById("date").value,
        time: document.getElementById("time").value
      };
      await fetch(API + "?action=save", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(payload) });
      calendar.refetchEvents();
      modal.classList.add("hidden");
    };

    document.getElementById("delete").onclick = async () => {
      await fetch(API + "?action=delete&id=" + data.id, { method: "DELETE" });
      calendar.refetchEvents();
      modal.classList.add("hidden");
    };

    document.getElementById("close").onclick = () => modal.classList.add("hidden");
  }

  // Default page
  loadPage("📊 Dashboard");
});
