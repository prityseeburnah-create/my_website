<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

include 'db.php'; 

try {
    $appCount = (int)$pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
    $msgCount = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
    $fixCount = (int)$pdo->query("SELECT COUNT(*) FROM fix_requests")->fetchColumn();
    $repairCount = (int)$pdo->query("SELECT COUNT(*) FROM repair_requests")->fetchColumn();
    $totalRequests = $fixCount + $repairCount;

    $messagesStmt = $pdo->query("SELECT id, name, email, subject, message, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 6");
    $recentMessages = $messagesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $appCount = $msgCount = $fixCount = $repairCount = $totalRequests = 0;
    $recentMessages = [];
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Garage Boowal — Admin Dashboard</title>

<style>
    :root{
      --bg:#000;
      --panel:#0f0f0f;
      --accent:#e10600;
      --muted:#aaa;
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,Helvetica,Arial,sans-serif;background:var(--bg);color:#fff}
    .wrap{display:flex;min-height:100vh}

    /* Sidebar */
    .sidebar{width:220px;background:linear-gradient(180deg,#0b0b0b,#0f0f0f);border-right:1px solid rgba(255,255,255,0.04);padding:22px;display:flex;flex-direction:column;gap:12px}
    .logo{display:flex;align-items:center;gap:10px;padding-bottom:8px;border-bottom:1px solid rgba(255,255,255,0.04)}
    .logo img{width:110px;height:auto}
    .menu{margin-top:12px;display:flex;flex-direction:column;gap:12px}
    .menu a{color:var(--accent);text-decoration:none;padding:8px 6px;border-radius:6px;display:block; font-weight:600}
    .menu a.inactive{color:#c4c4c4}
    .logout{margin-top:auto;padding-top:18px}
    .logout a{color:var(--accent);font-size:20px;text-decoration:none}

    /* Main content */
    .main{flex:1;padding:22px;overflow:auto}
    .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}
    .topbar .title{font-size:20px;color:var(--accent);font-weight:700}
    .date-btn{padding:6px 10px;border-radius:8px;border:1px solid rgba(255,255,255,0.08);background:transparent;color:#fff; cursor: pointer;}

    /* Stats cards */
    .cards{display:flex;gap:14px;margin-bottom:18px}
    .card{flex:1;padding:18px;border-radius:12px;background:linear-gradient(180deg,#111,#0b0b0b);border:1px solid rgba(255,255,255,0.04)}
    .card h3{margin:0;color:var(--accent);font-size:13px;font-weight:700}
    .card .num{font-size:28px;margin-top:8px;color:#fff;font-weight:800}

    /* bottom grid */
    .bottom-grid{display:grid;grid-template-columns:1fr 420px;gap:18px}

    /* messages box */
    .messages{padding:14px;border-radius:12px;background:var(--panel);border:1px solid rgba(255,255,255,0.04)}
    .messages h4{margin:0 0 10px 0;color:var(--accent)}
    .msg-list{display:flex;flex-direction:column;gap:8px;max-height:440px;overflow:auto}
    .msg-item{padding:10px;border-radius:8px;background:rgba(255,255,255,0.02);display:flex;flex-direction:column;gap:6px}
    .msg-item small{color:var(--muted)}

    /* calendar */
    .calendar{padding:14px;border-radius:12px;background:var(--panel);border:1px solid rgba(255,255,255,0.04);display:flex;flex-direction:column;align-items:stretch}
    .cal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
    .cal-controls button{background:transparent;border:1px solid rgba(255,255,255,0.06);color:#fff;padding:6px 10px;border-radius:8px;cursor:pointer;margin-left:8px}
    .month-title{font-weight:700;color:var(--accent)}
    .weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:6px;color:var(--muted);font-size:12px; text-align: center;}
    .grid{display:grid;grid-template-columns:repeat(7,1fr);gap:6px}
    .cell{min-height:70px;background:transparent;border-radius:8px;padding:6px;color:#fff;border:1px solid rgba(255,255,255,0.02);position:relative;cursor:pointer;transition:all .12s; text-align: right;}
    .cell:hover{background:rgba(255,255,255,0.02)}
    .cell .date-num{font-weight:700;color:#cfcfcf;font-size:13px}
    .cell .badge{position:absolute;right:6px;top:6px;background:var(--accent);color:#fff;padding:3px 6px;border-radius:10px;font-size:12px}
    .cell.today{box-shadow:0 0 8px rgba(225,6,0,0.18);border:1px solid rgba(225,6,0,0.35)}
    .cell.has-appointment{background:rgba(225,6,0,0.06);border:1px solid rgba(225,6,0,0.15)}

    /* modal */
    .modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);align-items:center;justify-content:center;padding:18px;z-index:2000}
    .modal{background:#0b0b0b;padding:18px;border-radius:10px;max-width:520px;color:#fff;border:1px solid rgba(255,255,255,0.06)}
    .modal h3{margin:0 0 10px 0;color:var(--accent)}
    .modal .appt-list{max-height:320px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;margin-top:8px}
    .appt-item{padding:10px;background:rgba(255,255,255,0.02);border-radius:8px;display:flex;flex-direction:column;gap:8px;} /* Change to column for better details fit */
    .appt-item > div:first-child{display:flex;justify-content:space-between;align-items:center;width:100%;}
    .btn{background:var(--accent);color:#fff;padding:8px 10px;border-radius:8px;border:0;cursor:pointer; font-weight: 600;}
    .btn.danger{background:#a20e0e}
    .small{font-size:13px;color:var(--muted)}

    /* Side Panel Styles */
    .tab-btn {
      opacity: 0.5; /* Mute inactive tabs */
      transition: opacity 0.2s;
    }
    .tab-btn.active {
      opacity: 1;
      box-shadow: 0 0 6px rgba(225,6,0,0.5); /* Subtle glow for active tab */
    }

    /* responsive */
    @media (max-width:980px){
      .wrap{flex-direction:column}
      .sidebar{width:100%;display:flex;flex-direction:row;gap:10px;overflow-x:auto; padding: 12px;}
      .logo{border-bottom:none;}
      .logo img{width: 80px;}
      .menu{flex-direction: row; margin-top: 0;}
      .logout{margin-top: 0; padding-top: 0;}
      .logout a{font-size: 16px;}
      .main{padding:12px}
      .bottom-grid{grid-template-columns:1fr}
      /* Ensure side panel is full screen on mobile */
      #sidePanel {
          width: 100% !important;
          right: -100% !important;
      }
    }
</style>
</head>
<body>
<div class="wrap">
    <aside class="sidebar" aria-label="Sidebar">
      <div class="logo">
        <img src="logo.png" alt="Garage Boowal logo" />
        <div style="font-size:13px;color:#fff">
          <div style="color:var(--accent);font-weight:800">GARAGE</div>
          <div style="color:#cfcfcf;font-weight:700">BOOWAL</div>
        </div>
      </div>
      <nav class="menu" aria-label="Main navigation">
        <a href="#dashboard" class="active">Dashboard</a>
        <a href="#" class="inactive" id="panelBtn" style="color:#fff; background:var(--accent)">Requests & Appointments</a>
        <a href="#messages" class="inactive" style="display:none;">Messages</a>
        <a href="#settings" class="inactive" style="display:none;">Settings</a>
      </nav>
      <div class="logout">
        <a href="logout.php" title="Logout">⟲ Logout</a>
      </div>
    </aside>

    <main class="main">
      <div class="topbar">
        <div class="title">Admin Dashboard</div>
        <div>
          <button class="date-btn" id="todayBtn"><?php echo date('F j, Y'); ?></button>
        </div>
      </div>

      <div class="cards">
        <div class="card">
          <h3>Appointments</h3>
          <div class="num"><?php echo $appCount; ?></div>
          <div class="small">Total</div>
        </div>
        <div class="card">
          <h3>Messages</h3>
          <div class="num"><?php echo $msgCount; ?></div>
          <div class="small">Total</div>
        </div>
        <div class="card">
          <h3>Requests</h3>
          <div class="num"><?php echo $totalRequests; ?></div>
          <div class="small">Fix + Repair</div>
        </div>
      </div>

      <div class="bottom-grid">
        <section class="messages">
          <h4>Recent Messages</h4>
          <div class="msg-list">
            <?php if (empty($recentMessages)): ?>
                <div style="padding:10px;color:var(--muted); text-align: center;">No contact messages found.</div>
            <?php endif; ?>
            <?php foreach($recentMessages as $m): ?>
              <div class="msg-item">
                <div style="display:flex;justify-content:space-between;align-items:center">
                  <strong><?php echo htmlspecialchars($m['name']); ?></strong>
                  <small class="small"><?php echo date('d M Y H:i', strtotime($m['created_at'])); ?></small>
                </div>
                <small class="small"><?php echo htmlspecialchars($m['email']); ?></small>
                <div style="margin-top:6px;color:#ddd"><?php echo htmlspecialchars(mb_strimwidth($m['message'],0,140,'...')); ?></div>
                <div style="margin-top:8px;display:flex;gap:8px">
                  <a class="small" href="mailto:<?php echo htmlspecialchars($m['email']); ?>" style="color: var(--accent); text-decoration: none;">Reply to message</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="calendar" aria-label="Appointment Calendar">
          <div class="cal-header">
            <div class="month-title" id="monthTitle">Month</div>
            <div class="cal-controls">
              <button id="prevMonth" title="Previous Month">◀</button>
              <button id="nextMonth" title="Next Month">▶</button>
            </div>
          </div>
          <div class="weekdays">
            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
          </div>
          <div class="grid" id="calendarGrid"></div>
        </section>
      </div>

    </main>
</div>

<div class="modal-backdrop" id="modalBackdrop">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <h3 id="modalTitle">Appointments</h3>
        <button id="closeModal" style="background:transparent;border:0;color:var(--muted);font-size:20px;cursor:pointer">✕</button>
      </div>
      <div id="modalDate" class="small" style="margin-top:6px;color:#cfcfcf"></div>
      <div class="appt-list" id="apptList"></div>
    </div>
</div>

<div id="sidePanel" style="
    position: fixed;
    right: -480px;
    top: 0;
    width: 480px;
    height: 100%;
    background: #0b0b0b;
    color: #fff;
    box-shadow: -8px 0 20px rgba(0,0,0,0.7);
    transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1); /* Smoother transition */
    z-index: 3000;
    overflow-y: auto;
    padding: 20px;
">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 10px;">
      <h3 id="panelTitle" style="color:var(--accent)">Requests & Appointments</h3>
      <button id="closeSidePanel" style="background:transparent;border:0;color:var(--muted);font-size:20px;cursor:pointer">✕</button>
    </div>
    <div style="display:flex;gap:8px;margin-bottom:15px; flex-wrap: wrap;">
      <button class="tab-btn btn active" data-type="appointments">Appointments</button>
      <button class="tab-btn btn" data-type="repair">Repair Requests</button>
    </div>
    <div id="panelLaist" style="display:block; max-height: calc(100vh - 150px); overflow-y: auto;"></div>
    <div id="panelDetails" style="display:none;margin-top:10px; padding: 10px; background: rgba(255,255,255,0.02); border-radius: 8px;"></div>
</div>

<script>
(function(){
  const grid = document.getElementById('calendarGrid');
  const monthTitle = document.getElementById('monthTitle');
  const prevBtn = document.getElementById('prevMonth');
  const nextBtn = document.getElementById('nextMonth');
  const modalBackdrop = document.getElementById('modalBackdrop');
  const apptList = document.getElementById('apptList');
  const modalDateEl = document.getElementById('modalDate');
  const closeModal = document.getElementById('closeModal');

  
  const panelBtn = document.getElementById('panelBtn');
  const sidePanel = document.getElementById('sidePanel');
  const closeSidePanel = document.getElementById('closeSidePanel');
  const panelList = document.getElementById('panelList');
  const panelDetails = document.getElementById('panelDetails');
  const panelTitle = document.getElementById('panelTitle');
  const tabBtns = document.querySelectorAll('.tab-btn');

  let today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();
  let currentType = 'appointments'; 

  function escapeHtml(str){
    if(str === null || str === undefined) return '';
    return String(str).replace(/[&<>"']/g, s=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s]));
  }


  function loadCalendar(month, year){
    grid.innerHTML = '';
    const dt = new Date(year, month, 1);
    monthTitle.textContent = dt.toLocaleString('default',{month:'long',year:'numeric'});

    const firstDay = dt.getDay();
    const totalDays = new Date(year, month+1,0).getDate();

    
    fetch(`get_appointments.php?month=${month+1}&year=${year}`)
      .then(res=>{
          if(!res.ok) throw new Error('Network response was not ok');
          return res.json();
      })
      .then(data=>{
        const grouped = {};
        data.forEach(a=>{
          const date = a.appointment_date || a.request_date;
          if(!date) return;
          const d = new Date(date);
          if(d.getFullYear() === year && d.getMonth() === month) {
              const day = d.getDate();
              if(!grouped[day]) grouped[day]=[];
              grouped[day].push(a);
          }
        });

        for(let i=0;i<firstDay;i++){
          const blank = document.createElement('div');
          blank.className='cell'; blank.style.opacity='0.1'; // Lowered opacity for better distinction
          blank.style.cursor='default';
          grid.appendChild(blank);
        }

        for(let day=1; day<=totalDays; day++){
          const cell = document.createElement('div');
          cell.className='cell';
          if(year===today.getFullYear() && month===today.getMonth() && day===today.getDate())
            cell.classList.add('today');

          const num = document.createElement('div'); num.className='date-num'; num.textContent=day;
          cell.appendChild(num);

          if(grouped[day]){
            cell.classList.add('has-appointment');
            const badge = document.createElement('div'); badge.className='badge';
            badge.textContent = grouped[day].length; cell.appendChild(badge);
            cell.addEventListener('click',()=>openModal(day,grouped[day],month,year));
          }else{
            cell.addEventListener('click',()=>openModal(day,[],month,year));
          }
          grid.appendChild(cell);
        }
      })
      .catch(err=>{
          console.error('Error fetching appointments for calendar:',err);
          grid.innerHTML = `<div style="grid-column: 1 / -1; color: var(--accent); padding: 10px; text-align: center;">Failed to load appointments. Check browser console.</div>`;
      });
  }

  function openModal(day, appts, month, year){
    modalDateEl.textContent = new Date(year,month,day).toLocaleDateString(undefined,{weekday:'long',year:'numeric',month:'long',day:'numeric'});
    apptList.innerHTML = '';
    
    // Check if the date is in the past for contextual feedback
    const dateToCheck = new Date(year, month, day);
    const todaySimple = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    const isPastDate = dateToCheck < todaySimple;

    if(!appts.length){
      apptList.innerHTML=`<div style="padding:10px;color:var(--muted); text-align: center;">No appointments on this day. ${isPastDate ? ' (Date is in the past)' : ''}</div>`;
      modalBackdrop.style.display='flex';
      return;
    }

    appts.forEach(a=>{
      const item=document.createElement('div'); item.className='appt-item';
      const header=document.createElement('div');
      header.innerHTML=`<strong>${escapeHtml(a.name||a.client_name||'Unknown')}</strong><div class="small">${escapeHtml(a.appointment_time||'')}</div>`;
      
      const right=document.createElement('div');
      const detailsBtn=document.createElement('button'); detailsBtn.className='btn'; detailsBtn.textContent='Details';
      detailsBtn.style.marginRight='8px';
      
      detailsBtn.addEventListener('click',()=>{
        let existing = item.querySelector('.details-panel');
        if(existing){
            existing.remove(); // Toggle close
            detailsBtn.textContent = 'Details';
            return;
        }
        detailsBtn.textContent = 'Hide'; // Toggle open

        // Simple way to ensure only one detail panel is open at a time in the modal
        document.querySelectorAll('.details-panel').forEach(p => p.remove());
        document.querySelectorAll('.appt-item button').forEach(btn => {
            if (btn !== detailsBtn) btn.textContent = 'Details';
        });

        const panel=document.createElement('div'); panel.className='details-panel';
        panel.innerHTML=`
          <div class="small"><strong>Email:</strong> ${escapeHtml(a.email||'N/A')}</div>
          <div class="small"><strong>Date:</strong> ${escapeHtml(a.appointment_date||'N/A')}</div>
          <div class="small"><strong>Time:</strong> ${escapeHtml(a.appointment_time||'N/A')}</div>
          <div class="small"><strong>Subject:</strong> ${escapeHtml(a.subject||'N/A')}</div>
          <div class="small" style="margin-top: 8px;"><strong>Message:</strong> ${escapeHtml(a.message||'N/A')}</div>
          ${a.id ? `<div style="margin-top:8px;"><small style="color:var(--accent)">ID: ${a.id}</small></div>` : ''}
        `;
        panel.style.background='rgba(255,255,255,0.03)'; panel.style.padding='8px'; panel.style.marginTop='6px'; panel.style.borderRadius='6px';
        item.appendChild(panel);
      });
      
      right.appendChild(detailsBtn);
      header.appendChild(right); // Add button to the right side of the header
      item.appendChild(header); 
      apptList.appendChild(item);
    });

    modalBackdrop.style.display='flex';
  }

  // Calendar Event Listeners
  closeModal.addEventListener('click',()=> modalBackdrop.style.display='none');
  prevBtn.addEventListener('click',()=>{currentMonth--; if(currentMonth<0){currentMonth=11; currentYear--;} loadCalendar(currentMonth,currentYear);});
  nextBtn.addEventListener('click',()=>{currentMonth++; if(currentMonth>11){currentMonth=0; currentYear++;} loadCalendar(currentMonth,currentYear);});
  
  document.getElementById('todayBtn').addEventListener('click', () => {
    today = new Date(); 
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    loadCalendar(currentMonth, currentYear);
  });
  
  loadCalendar(currentMonth,currentYear); // Initial load

  // --- Side Panel Logic ---

  // Function to set the active state of the panel buttons
  function setActiveTab(type) {
    tabBtns.forEach(btn => {
      btn.classList.remove('active');
      if (btn.dataset.type === type) {
        btn.classList.add('active');
      }
    });
  }

  panelBtn.addEventListener('click',()=>{
    sidePanel.style.right='0';
    panelList.style.display='block';
    panelDetails.style.display='none';
    setActiveTab(currentType); // Set initial active tab
    loadPanelData(currentType);
  });

  closeSidePanel.addEventListener('click',()=> sidePanel.style.right='-480px');

  tabBtns.forEach(btn=>{
    btn.addEventListener('click',()=>{
      currentType = btn.dataset.type;
      panelList.style.display='block';
      panelDetails.style.display='none';
      setActiveTab(currentType); // Set active tab on click
      loadPanelData(currentType);
    });
  });

  function loadPanelData(type){
    let url, label;
    switch(type){
      case 'appointments': url='get_appointments.php'; label='Appointments'; break;
      case 'fix': url='get_fix_requests.php'; label='Fix Requests'; break;
      case 'repair': url='get_repair_requests.php'; label='Repair Requests'; break;
      default: return;
    }
    panelTitle.textContent = label;
    panelList.innerHTML = `<div style="color:var(--accent); text-align:center; padding: 20px;">Loading ${label}...</div>`; // Loading indicator

    fetch(url)
      .then(res=>{
          if(!res.ok) throw new Error('Network response was not ok');
          return res.json();
      })
      .then(data=>{
        // Sort by most recent date (assuming created_at or request_date exists)
        data.sort((a, b) => new Date(b.created_at || b.request_date) - new Date(a.created_at || a.request_date));

        panelList.innerHTML='';
        if(!data.length){
          panelList.innerHTML=`<div style="color:var(--muted); padding:10px; text-align: center;">No ${label.toLowerCase()} found.</div>`;
          return;
        }
        data.forEach(item=>{
          const div=document.createElement('div');
          div.className='msg-item';
          div.style.cursor='pointer';
          let subtitle='';
          const requestDate = item.created_at || item.request_date || '';

          if(type==='appointments'){ 
            subtitle=item.appointment_time||''; 
          }
          else { 
            subtitle=item.damage_description?.substring(0,80)||item.subject?.substring(0,80)||item.message?.substring(0,80)||'No description'; 
          }
          
          div.innerHTML=`
            <strong>${escapeHtml(item.name||item.client_name||item.user_name||'Unknown')}</strong>
            <div class="small">${requestDate ? new Date(requestDate).toLocaleString() : 'Date N/A'}</div>
            <div style="color:#ddd;margin-top:4px">${escapeHtml(subtitle)}</div>
          `;
          div.addEventListener('click',()=>showPanelDetails(type,item));
          panelList.appendChild(div);
        });
      })
      .catch(err=>{
          console.error('Error loading',type,err);
          panelList.innerHTML = `<div style="color:var(--accent); padding:10px; text-align: center;">Error loading ${label}: ${err.message}. Check browser console.</div>`;
      });
  }

  function showPanelDetails(type,item){
    panelList.style.display='none';
    panelDetails.style.display='block';
    panelDetails.innerHTML = ''; // Clear previous details

    let html = '';
    const name = escapeHtml(item.name||item.client_name||item.user_name||'N/A');
    const email = escapeHtml(item.email||item.user_email||'N/A');
    const phone = escapeHtml(item.user_phone||'N/A');
    const date = new Date(item.created_at||item.request_date).toLocaleDateString() || 'N/A';
    const time = escapeHtml(item.appointment_time||'N/A');
    const subject = escapeHtml(item.subject||'N/A');
    const message = escapeHtml(item.message||'N/A');
    const id = item.id || 'N/A';

    html += `<div style="font-weight: 700; color: #fff; margin-bottom: 10px;">Details for ID: ${id}</div>`;

    if(type==='appointments'){
      html+=`
        <div><strong>Name:</strong> ${name}</div>
        <div><strong>Email:</strong> ${email}</div>
        <div><strong>Date:</strong> ${date}</div>
        <div><strong>Time:</strong> ${time}</div>
        <div><strong>Subject:</strong> ${subject}</div>
        <div><strong>Message:</strong> ${message}</div>
      `;
    } else if(type==='fix'){
      html+=`
        <div><strong>Name:</strong> ${name}</div>
        <div><strong>Email:</strong> ${email}</div>
        <div><strong>Date:</strong> ${date}</div>
        <div><strong>Subject:</strong> ${subject}</div>
        <div><strong>Details:</strong> ${message}</div>
      `;
    } else if(type==='repair'){
      const vehicle = escapeHtml(item.vehicle_make_model||'N/A');
      const year = escapeHtml(item.vehicle_year||'N/A');
      const description = escapeHtml(item.damage_description||'N/A');

      html+=`
        <div><strong>Name:</strong> ${name}</div>
        <div><strong>Email:</strong> ${email}</div>
        <div><strong>Phone:</strong> ${phone}</div>
        <div><strong>Vehicle:</strong> ${vehicle} (${year})</div>
        <div><strong>Request Date:</strong> ${date}</div>
        <div style="margin-bottom:10px;"><strong>Description:</strong> ${description}</div>
      `;
      
      // --- FIX for Images (Enhanced) ---
      try{
        const photos = JSON.parse(item.photo_urls||'[]');
        if(Array.isArray(photos) && photos.length){
          html += '<div style="margin-top:10px; border-top: 1px solid rgba(255,255,255,0.1); padding-top:10px;"><strong>Attached Photos:</strong></div>';
          
          html += photos.map(url=>`
            <div style="margin: 8px 0; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; overflow: hidden; background: #000;">
              <a href="${escapeHtml(url)}" target="_blank" title="View Full Image">
                <img src="${escapeHtml(url)}" alt="Damage photo" style="max-width:100%; display:block; height: auto;">
              </a>
            </div>
          `).join('');
        }
      }catch(e){
        console.error("Error parsing photo_urls:", e);
        html += '<div style="color:var(--accent); margin-top:10px;">Error parsing photo URLs or no photos attached.</div>';
      }
    }
    
    html += `<button class="btn" style="margin-top:15px" onclick="document.getElementById('panelList').style.display='block'; document.getElementById('panelDetails').style.display='none'">← Back to List</button>`;
    
    panelDetails.innerHTML = html;
  }
  
  // Set initial active tab state visually on load
  setActiveTab(currentType);

})();
</script>
</body>
</html>