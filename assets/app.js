document.addEventListener('DOMContentLoaded', ()=> {
  const form = document.getElementById('genForm');
  const msg = document.getElementById('genMsg');

  document.getElementById('regenSeed').addEventListener('click', ()=>{
    // optional UX: random seed copied to clipboard
    const s = 'seed-' + Math.floor(Math.random()*1e9);
    navigator.clipboard?.writeText(s).catch(()=>{});
    alert('Random seed generated and copied to clipboard: ' + s);
  });

  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const data = new FormData(form);
    const res = await fetch('generate.php', {method:'POST', body:data});
    const j = await res.json();
    if (!j.ok) { msg.textContent = j.error || 'Error'; return; }
    msg.innerHTML = `Generated — <a href="view.php?id=${j.id}">View challenge</a><br>Signature: <code>${j.signature}</code>`;
    // optionally reload list slowly so new item appears
    setTimeout(()=> location.reload(), 900);
  });
});
