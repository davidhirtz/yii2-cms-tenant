export default(s,a)=>{const t=document.querySelector(s),e=document.querySelector(a),o=()=>{e.options[0].dataset.value=t.options[t.selectedIndex].dataset.value,e.dispatchEvent(new Event("change"))};t.addEventListener("change",function(){const r=new URL(window.location.href);r.searchParams.set("tenant",t.value),e.disabled=!0,fetch(r).then(n=>n.text()).then(n=>{const c=new DOMParser().parseFromString(n,"text/html").querySelector(a);e.innerHTML=c.innerHTML||"",e.disabled=!1,o()})}),o()};
