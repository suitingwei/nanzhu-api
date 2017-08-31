/*! jquery-html5Validate.js 基于HTML5表单验证的jQuery插件
**/
!function(a,b){DBC2SBC=function(a){var c,d,b="";for(c=0;c<a.length;c++)d=a.charCodeAt(c),b+=d>=65281&&65373>=d?String.fromCharCode(a.charCodeAt(c)-65248):12288==d?String.fromCharCode(a.charCodeAt(c)-12288+32):a.charAt(c);return b},a.testRemind=function(){var b=a(window).width(),c=function(b){b&&b.target&&b.target.id!==a.testRemind.id&&0===a(b.target).parents("#"+a.testRemind.id).length&&a.testRemind.hide()},d=function(b){b&&b.target&&"body"!==b.target.tagName.toLowerCase()&&a.testRemind.hide()},e=function(){if(a.testRemind.display){var c=a(window).width();Math.abs(b-c)>20&&(a.testRemind.hide(),b=c)}};return{id:"validateRemind",display:!1,css:{},hide:function(){a("#"+this.id).remove(),this.display=!1,this.target&&this.target.removeClass("error"),a(document).unbind({mousedown:c,keydown:d}),a(window).unbind("resize",e)},bind:function(){a(document).bind({mousedown:c,keydown:d}),a(window).bind("resize",e)}}}(),OBJREG={EMAIL:"^[a-z0-9._%-]+@([a-z0-9-]+\\.)+[a-z]{2,4}$",NUMBER:"^\\-?\\d+(\\.\\d+)?$",URL:"^(http|https|ftp)\\:\\/\\/[a-z0-9\\-\\.]+\\.[a-z]{2,3}(:[a-z0-9]*)?\\/?([a-z0-9\\-\\._\\?\\,\\'\\/\\\\\\+&amp;%\\$#\\=~])*$",TEL:"^1\\d{10}$",ZIPCODE:"^\\d{6}$",prompt:{radio:"\u8bf7\u9009\u62e9\u4e00\u4e2a\u9009\u9879",checkbox:"\u5982\u679c\u8981\u7ee7\u7eed\uff0c\u8bf7\u9009\u4e2d\u6b64\u6846",select:"\u8bf7\u9009\u62e9\u5217\u8868\u4e2d\u7684\u4e00\u9879",email:"\u8bf7\u8f93\u5165\u7535\u5b50\u90ae\u4ef6\u5730\u5740",url:"\u8bf7\u8f93\u5165\u7f51\u7ad9\u5730\u5740",tel:"\u8bf7\u8f93\u5165\u624b\u673a\u53f7\u7801",number:"\u8bf7\u8f93\u5165\u6570\u503c",date:"\u8bf7\u8f93\u5165\u65e5\u671f",pattern:"\u5185\u5bb9\u683c\u5f0f\u4e0d\u7b26\u5408\u8981\u6c42",empty:"\u8bf7\u586b\u5199\u6b64\u5b57\u6bb5",multiple:"\u591a\u6761\u6570\u636e\u4f7f\u7528\u9017\u53f7\u5206\u9694"}},a.html5Attr=function(c,d){if(!c||!d)return b;if(document.querySelector)return a(c).attr(d);var e;return e=c.getAttributeNode(d),e&&""!==e.nodeValue?e.nodeValue:b},a.html5Validate=function(){return{isSupport:function(){return"email"===a('<input type="email">').attr("type")}(),isEmpty:function(b,c){c=c||a.html5Attr(b,"placeholder");var d=b.value;return"password"!==b.type&&(d=a.trim(d)),""===d||d===c?!0:!1},isRegex:function(b,c,d){var e=b.value,f=e,g=b.getAttribute("type")+"";if(g=g.replace(/\W+$/,""),"password"!==g&&(f=a.trim(e),"text"!==g&&"null"!==g&&"textarea"!=b.tagName.toLowerCase()&&(f=DBC2SBC(f)),f!==e&&a(b).val(f)),c=c||function(){return a.html5Attr(b,"pattern")}()||function(){return g&&a.map(g.split("|"),function(a){var b=OBJREG[a.toUpperCase()];return b?b:void 0}).join("|")}(),""===f||!c)return!0;var h=a(b).hasProp("multiple"),i=new RegExp(c,d||"i");if(h&&!/^number|range$/i.test(g)){var j=!0;return a.each(f.split(","),function(b,c){c=a.trim(c),j&&!i.test(c)&&(j=!1)}),j}return i.test(f)},isOverflow:function(b){if(!b)return!1;var e,f,g,c=a(b).attr("min"),d=a(b).attr("max"),h=b.value;if(c||d){if(h=Number(h),e=Number(a(b).attr("step"))||1,c&&c>h)a(b).testRemind("\u503c\u5fc5\u987b\u5927\u4e8e\u6216\u7b49\u4e8e"+c);else if(d&&h>d)a(b).testRemind("\u503c\u5fc5\u987b\u5c0f\u4e8e\u6216\u7b49\u4e8e"+d);else{if(!e||/^\d+(\.0+)?$/.test((Math.abs(h-c||0)/e).toFixed(10)))return!1;a(b).testRemind("\u503c\u65e0\u6548")}b.focus(),b.select()}else if(f=a(b).attr("data-min"),g=a(b).attr("data-max"),f&&h.length<f)a(b).testRemind("\u81f3\u5c11\u8f93\u5165"+f+"\u4e2a\u5b57\u7b26"),b.focus();else{if(!(g&&h.length>g))return!1;a(b).testRemind("\u6700\u591a\u8f93\u5165"+g+"\u4e2a\u5b57\u7b26"),a(b).selectRange(g,h.length)}return!0},isAllpass:function(b,c){if(!b)return!0;var d={labelDrive:!0};params=a.extend({},d,c||{}),b.size&&1==b.size()&&"form"==b.get(0).tagName.toLowerCase()?b=b.find(":input"):b.tagName&&"form"==b.tagName.toLowerCase()&&(b=a(b).find(":input"));var e=this,f=!0,g=function(b,c,d){var h,e=a(b).attr("data-key"),f=a("label[for='"+b.id+"']"),g="";if(params.labelDrive&&(h=a.html5Attr(b,"placeholder"),f.each(function(){var b=a(this).text();b!==h&&(g+=b.replace(/\*|:|\uff1a/g,""))})),a(b).isVisible())if("radio"==c||"checkbox"==c)a(b).testRemind(OBJREG.prompt[c],{align:"left"}),b.focus();else if("select"==d||"empty"==d)a(b).testRemind("empty"==d&&g?"\u60a8\u5c1a\u672a\u8f93\u5165"+g:OBJREG.prompt[d]),b.focus();else if(/^range|number$/i.test(c)&&Number(b.value))a(b).testRemind("\u503c\u65e0\u6548"),b.focus(),b.select();else{var i=OBJREG.prompt[c]||OBJREG.prompt.pattern;g&&(i="\u60a8\u8f93\u5165\u7684"+g+"\u683c\u5f0f\u4e0d\u51c6\u786e"),"number"!=c&&a(b).hasProp("multiple")&&(i+="\uff0c"+OBJREG.prompt.multiple),a(b).testRemind(i),b.focus(),b.select()}else{var j=a(b).attr("data-target"),k=a("#"+j);0==k.size()&&(k=a("."+j));var l="\u60a8\u5c1a\u672a"+(e||("empty"==d?"\u8f93\u5165":"\u9009\u62e9"))+(!/^radio|checkbox$/i.test(c)&&g||"\u8be5\u9879\u5185\u5bb9");k.size()?(k.offset().top<a(window).scrollTop()&&a(window).scrollTop(k.offset().top-50),k.testRemind(l)):alert(l)}return!1};return b.each(function(){var b=this,c=b.getAttribute("type"),d=b.tagName.toLowerCase(),h=a(this).hasProp("required");if(c){var i=c.replace(/\W+$/,"");if(!params.hasTypeNormally&&a.html5Validate.isSupport&&c!=i)try{b.type=i}catch(j){}c=i}if(0!=f&&!b.disabled&&"submit"!=c&&"reset"!=c&&"file"!=c&&"image"!=c)if("radio"==c&&h){var k=a(b.name?"input[type='radio'][name='"+b.name+"']":b),l=!1;k.each(function(){0==l&&a(this).is(":checked")&&(l=!0)}),0==l&&(f=g(k.get(0),c,d))}else"checkbox"==c&&h&&!a(b).is(":checked")?f=g(b,c,d):"select"==d&&h&&!b.value?f=g(b,c,d):h&&e.isEmpty(b)||!(f=e.isRegex(b))?(f?g(b,c,"empty"):g(b,c,d),f=!1):e.isOverflow(b)&&(f=!1)}),f}}}(),a.fn.extend({isVisible:function(){return"hidden"!==a(this).attr("type")&&"none"!==a(this).css("display")&&"hidden"!==a(this).css("visibility")},hasProp:function(c){if("string"!=typeof c)return b;var d=!1;if(document.querySelector){var e=a(this).attr(c);e!==b&&e!==!1&&(d=!0)}else{var f=a(this).get(0).outerHTML,g=f.slice(0,f.search(/\/?['"]?>(?![^<]*<['"])/));d=new RegExp("\\s"+c+"\\b","i").test(g)}return d},selectRange:function(b,c){var d=a(this).get(0);if(d.createTextRange){var e=d.createTextRange();e.collapse(!0),e.moveEnd("character",c),e.moveStart("character",b),e.select()}else d.focus(),d.setSelectionRange(b,c);return this},testRemind:function(b,c){var d={size:6,align:"center",css:{maxWidth:280,backgroundColor:"#333",borderColor:"#333",color:"#fff",fontSize:"14px",padding:"5px 15px",borderRadius:"50px",zIndex:202}};c=c||{},c.css=a.extend({},d.css,c.css||a.testRemind.css);var e=a.extend({},d,c||{});if(b&&a(this).isVisible()){var f={center:"50%",left:"15%",right:"85%"},g=f[e.align]||"50%";e.css.position="absolute",e.css.top="-99px",e.css.border="1px solid "+e.css.borderColor,a("#"+a.testRemind.id).size()&&a.testRemind.hide(),this.remind=a('<div id="'+a.testRemind.id+'">'+b+"</div>").css(e.css),a(document.body).append(this.remind);var h;!window.XMLHttpRequest&&(h=parseInt(e.css.maxWidth))&&this.remind.width()>h&&this.remind.width(h);var i=a(this).offset(),j="top";if(!i)return a(this);var k=i.top-this.remind.outerHeight()-e.size;k<a(document).scrollTop()&&(j="bottom",k=i.top+a(this).outerHeight()+e.size);var l=function(b){var c="transparent",d="dashed",f="solid",g={},h={width:0,height:0,overflow:"hidden",borderWidth:e.size+"px",position:"absolute"},i={};if("before"===b)g={top:{borderColor:[e.css.borderColor,c,c,c].join(" "),borderStyle:[f,d,d,d].join(" "),top:0},bottom:{borderColor:[c,c,e.css.borderColor,""].join(" "),borderStyle:[d,d,f,d].join(" "),bottom:0}};else{if("after"!==b)return g=null,h=null,i=null,null;g={top:{borderColor:e.css.backgroundColor+["",c,c,c].join(" "),borderStyle:[f,d,d,d].join(" "),top:-1},bottom:{borderColor:[c,c,e.css.backgroundColor,""].join(" "),borderStyle:[d,d,f,d].join(" "),bottom:-1}}}return i=a.extend({},g[j],h),a("<"+b+"></"+b+">").css(i)},m={width:2*e.size,left:g,marginLeft:-1*e.size+"px",height:e.size,textIndent:0,overflow:"hidden",position:"absolute"};return"top"==j?m.bottom=-1*e.size:m.top=-1*e.size,this.remind.css({left:i.left,top:k,marginLeft:.5*a(this).outerWidth()-this.remind.outerWidth()*parseInt(g)/100}).prepend(a("<div></div>").css(m).append(l("before")).append(l("after"))),a.testRemind.display=!0,a.testRemind.target=a(this).addClass("error"),a.testRemind.bind(),a(this)}},html5Validate:function(b,c){var d={novalidate:!0,submitEnabled:!0,validate:function(){return!0}},e=a.extend({},d,c||{});return a.html5Validate.isSupport&&(e.novalidate?a(this).attr("novalidate","novalidate"):e.hasTypeNormally=!0),e.submitEnabled&&a(this).find(":disabled").each(function(){/^image|submit$/.test(this.type)&&a(this).removeAttr("disabled")}),a(this).bind("submit",function(c){var d=a(this).find(":input");return d.each(function(){var a=this.getAttribute("type")+"",b=a.replace(/\W+$/,"");if(a!=b)try{this.type=b}catch(c){}}),a.html5Validate.isAllpass(d,e)&&e.validate()&&a.isFunction(b)&&b.call(this),c.preventDefault(),!1}),a(this)}})}(jQuery);