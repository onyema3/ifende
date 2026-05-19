(function(){
'use strict';

/* CURSOR */
var cursor=document.getElementById('cursor');
var ring=document.getElementById('cursorRing');
if(cursor&&ring){
  document.addEventListener('mousemove',function(e){
    cursor.style.left=e.clientX+'px'; cursor.style.top=e.clientY+'px';
    setTimeout(function(){ ring.style.left=e.clientX+'px'; ring.style.top=e.clientY+'px'; },80);
  });
  document.querySelectorAll('a,button').forEach(function(el){
    el.addEventListener('mouseenter',function(){ cursor.style.width='24px'; cursor.style.height='24px'; ring.style.width='56px'; ring.style.height='56px'; ring.style.borderColor='var(--green)'; });
    el.addEventListener('mouseleave',function(){ cursor.style.width='12px'; cursor.style.height='12px'; ring.style.width='36px'; ring.style.height='36px'; ring.style.borderColor='rgba(33,161,78,0.5)'; });
  });
}

/* NAV SCROLL */
var nav=document.getElementById('siteNav');
if(nav){ window.addEventListener('scroll',function(){ nav.classList.toggle('scrolled',window.scrollY>60); },{passive:true}); }

/* MOBILE DRAWER */
window.toggleDrawer=function(){
  var btn=document.getElementById('hamburger');
  var drawer=document.getElementById('mobileDrawer');
  if(!btn||!drawer) return;
  var open=btn.classList.contains('open');
  btn.classList.toggle('open',!open);
  drawer.classList.toggle('open',!open);
  document.body.style.overflow=open?'':'hidden';
};
document.querySelectorAll('#mobileDrawer a').forEach(function(a){
  a.addEventListener('click',function(){
    document.getElementById('hamburger').classList.remove('open');
    document.getElementById('mobileDrawer').classList.remove('open');
    document.body.style.overflow='';
  });
});

/* SCROLL REVEAL */
var reveals=document.querySelectorAll('.reveal');
if('IntersectionObserver' in window){
  var ro=new IntersectionObserver(function(entries){
    entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('visible'); ro.unobserve(e.target); } });
  },{threshold:0.1});
  reveals.forEach(function(r){ ro.observe(r); });
} else { reveals.forEach(function(r){ r.classList.add('visible'); }); }

/* MARQUEE */
var track=document.getElementById('marqueeTrack');
if(track){ track.innerHTML+=track.innerHTML; }

/* CONTACT FORM */
var form=document.getElementById('contactForm');
if(form){
  form.addEventListener('submit',function(e){
    e.preventDefault();
    var btn=document.getElementById('submitBtn');
    var msg=document.getElementById('formMsg');
    btn.textContent='Sending...'; btn.classList.add('loading');
    var name=(document.getElementById('fname').value+' '+document.getElementById('lname').value).trim();
    var email=document.getElementById('femail').value;
    var subject=document.getElementById('fsubject')?document.getElementById('fsubject').value:'Portfolio Enquiry';
    var message=document.getElementById('fmessage').value;
    var data={_subject:'Portfolio Enquiry: '+subject,_replyto:email,name:name,email:email,subject:subject,message:message};
    var fs=ifendeData.formspree||'';
    var w3=ifendeData.web3forms||'';
    var fallback=ifendeData.email||'hello@ifende.com';

    /* Try WP AJAX first */
    var fd=new FormData();
    fd.append('action','ifende_contact'); fd.append('nonce',ifendeData.nonce);
    fd.append('name',name); fd.append('email',email); fd.append('subject',subject); fd.append('message',message);
    fetch(ifendeData.ajaxUrl,{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(res){
      if(res.success){ showSuccess(btn,msg); }
      else if(fs){ tryFormspree(data,fs,btn,msg,fallback); }
      else if(w3){ tryWeb3(data,w3,btn,msg,fallback); }
      else{ doMailto(data,fallback,btn,msg); }
    })
    .catch(function(){ fs?tryFormspree(data,fs,btn,msg,fallback):doMailto(data,fallback,btn,msg); });
  });
}

function tryFormspree(data,id,btn,msg,fb){
  fetch('https://formspree.io/f/'+id,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(data)})
  .then(function(r){ r.ok?showSuccess(btn,msg):doMailto(data,fb,btn,msg); })
  .catch(function(){ doMailto(data,fb,btn,msg); });
}
function tryWeb3(data,key,btn,msg,fb){
  fetch('https://api.web3forms.com/submit',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(Object.assign({},data,{access_key:key}))})
  .then(function(r){ r.ok?showSuccess(btn,msg):doMailto(data,fb,btn,msg); })
  .catch(function(){ doMailto(data,fb,btn,msg); });
}
function doMailto(data,email,btn,msg){
  var body='Name: '+data.name+'\nEmail: '+data.email+'\n\nMessage:\n'+data.message;
  window.location.href='mailto:'+email+'?subject='+encodeURIComponent(data._subject)+'&body='+encodeURIComponent(body);
  setTimeout(function(){ showSuccess(btn,msg); },1000);
}
function showSuccess(btn,msg){
  btn.textContent='Message Sent ✓'; btn.classList.remove('loading');
  if(msg){ msg.style.display='block'; msg.textContent="Thank you! I'll get back to you shortly."; }
}

})();
