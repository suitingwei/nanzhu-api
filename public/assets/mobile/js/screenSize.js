function getRem(pwith,prem){
    var html=document.getElementsByTagName('html')[0];
    var owidth=document.body.clientWidth || document.documentElement.clientWidth;
    html.style.fontSize=(owidth/pwith)*prem+'px';
}
