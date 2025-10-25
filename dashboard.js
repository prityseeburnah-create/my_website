const ctx=document.getElementById('myChart');
if(ctx){
  new Chart(ctx,{
    type:'bar',
    data:{
      labels:['Appointments','Cars','Stock'],
      datasets:[{
        label:'Overview',
        data:[
          document.querySelectorAll('.card')[0].innerText.split(': ')[1],
          document.querySelectorAll('.card')[1].innerText.split(': ')[1],
          document.querySelectorAll('.card')[2].innerText.split(': ')[1]
        ],
        backgroundColor:['#e53935','#ff6b6b','#ffcc00']
      }]
    }
  });
}
