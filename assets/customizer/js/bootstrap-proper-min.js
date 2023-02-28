!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.Popper=t()}(this,function(){"use strict";function e(e){return e&&"[object Function]"==={}.toString.call(e)}function t(e,t){if(1!==e.nodeType)return[];var n=window.getComputedStyle(e,null);return t?n[t]:n}function n(e){return"HTML"===e.nodeName?e:e.parentNode||e.host}function o(e){if(!e||-1!==["HTML","BODY","#document"].indexOf(e.nodeName))return window.document.body;var r=t(e),i=r.overflow,f=r.overflowX,s=r.overflowY;return/(auto|scroll)/.test(i+s+f)?e:o(n(e))}function r(e){var n=e&&e.offsetParent,o=n&&n.nodeName;return o&&"BODY"!==o&&"HTML"!==o?-1!==["TD","TABLE"].indexOf(n.nodeName)&&"static"===t(n,"position")?r(n):n:window.document.documentElement}function i(e){return null===e.parentNode?e:i(e.parentNode)}function f(e,t){if(!(e&&e.nodeType&&t&&t.nodeType))return window.document.documentElement;var n=e.compareDocumentPosition(t)&Node.DOCUMENT_POSITION_FOLLOWING,o=n?e:t,s=n?t:e,a=document.createRange();a.setStart(o,0),a.setEnd(s,0);var p=a.commonAncestorContainer;if(e!==p&&t!==p||o.contains(s))return function(e){var t=e.nodeName;return"BODY"!==t&&("HTML"===t||r(e.firstElementChild)===e)}(p)?p:r(p);var u=i(e);return u.host?f(u.host,t):f(e,i(t).host)}function s(e){var t="top"===(1<arguments.length&&void 0!==arguments[1]?arguments[1]:"top")?"scrollTop":"scrollLeft",n=e.nodeName;if("BODY"===n||"HTML"===n){var o=window.document.documentElement;return(window.document.scrollingElement||o)[t]}return e[t]}function a(e,t){var n=2<arguments.length&&void 0!==arguments[2]&&arguments[2],o=s(t,"top"),r=s(t,"left"),i=n?-1:1;return e.top+=o*i,e.bottom+=o*i,e.left+=r*i,e.right+=r*i,e}function p(e,t){var n="x"===t?"Left":"Top",o="Left"==n?"Right":"Bottom";return+e["border"+n+"Width"].split("px")[0]+ +e["border"+o+"Width"].split("px")[0]}function u(e,t,n,o){return S(t["offset"+e],n["client"+e],n["offset"+e],G()?n["offset"+e]+o["margin"+("Height"===e?"Top":"Left")]+o["margin"+("Height"===e?"Bottom":"Right")]:0)}function d(){var e=window.document.body,t=window.document.documentElement,n=G()&&window.getComputedStyle(t);return{height:u("Height",e,t,n),width:u("Width",e,t,n)}}function l(e){return J({},e,{right:e.left+e.width,bottom:e.top+e.height})}function c(e){var n={};if(G())try{n=e.getBoundingClientRect();var o=s(e,"top"),r=s(e,"left");n.top+=o,n.left+=r,n.bottom+=o,n.right+=r}catch(e){}else n=e.getBoundingClientRect();var i={left:n.left,top:n.top,width:n.right-n.left,height:n.bottom-n.top},f="HTML"===e.nodeName?d():{},a=f.width||e.clientWidth||i.right-i.left,u=f.height||e.clientHeight||i.bottom-i.top,c=e.offsetWidth-a,h=e.offsetHeight-u;if(c||h){var m=t(e);c-=p(m,"x"),h-=p(m,"y"),i.width-=c,i.height-=h}return l(i)}function h(e,n){var r=G(),i="HTML"===n.nodeName,f=c(e),s=c(n),p=o(e),u=t(n),d=+u.borderTopWidth.split("px")[0],h=+u.borderLeftWidth.split("px")[0],m=l({top:f.top-s.top-d,left:f.left-s.left-h,width:f.width,height:f.height});if(m.marginTop=0,m.marginLeft=0,!r&&i){var g=+u.marginTop.split("px")[0],v=+u.marginLeft.split("px")[0];m.top-=d-g,m.bottom-=d-g,m.left-=h-v,m.right-=h-v,m.marginTop=g,m.marginLeft=v}return(r?n.contains(p):n===p&&"BODY"!==p.nodeName)&&(m=a(m,n)),m}function m(e){var t=window.document.documentElement,n=h(e,t),o=S(t.clientWidth,window.innerWidth||0),r=S(t.clientHeight,window.innerHeight||0),i=s(t),f=s(t,"left");return l({top:i-n.top+n.marginTop,left:f-n.left+n.marginLeft,width:o,height:r})}function g(e){var o=e.nodeName;return"BODY"!==o&&"HTML"!==o&&("fixed"===t(e,"position")||g(n(e)))}function v(e,t,r,i){var s={top:0,left:0},a=f(e,t);if("viewport"===i)s=m(a);else{var p;"scrollParent"===i?"BODY"===(p=o(n(e))).nodeName&&(p=window.document.documentElement):p="window"===i?window.document.documentElement:i;var u=h(p,a);if("HTML"!==p.nodeName||g(a))s=u;else{var l=d(),c=l.height,v=l.width;s.top+=u.top-u.marginTop,s.bottom=c+u.top,s.left+=u.left-u.marginLeft,s.right=v+u.left}}return s.left+=r,s.top+=r,s.right-=r,s.bottom-=r,s}function b(e){return e.width*e.height}function w(e,t,n,o,r){var i=5<arguments.length&&void 0!==arguments[5]?arguments[5]:0;if(-1===e.indexOf("auto"))return e;var f=v(n,o,i,r),s={top:{width:f.width,height:t.top-f.top},right:{width:f.right-t.right,height:f.height},bottom:{width:f.width,height:f.bottom-t.bottom},left:{width:t.left-f.left,height:f.height}},a=Object.keys(s).map(function(e){return J({key:e},s[e],{area:b(s[e])})}).sort(function(e,t){return t.area-e.area}),p=a.filter(function(e){var t=e.width,o=e.height;return t>=n.clientWidth&&o>=n.clientHeight}),u=0<p.length?p[0].key:a[0].key,d=e.split("-")[1];return u+(d?"-"+d:"")}function y(e,t,n){return h(n,f(t,n))}function O(e){var t=window.getComputedStyle(e),n=parseFloat(t.marginTop)+parseFloat(t.marginBottom),o=parseFloat(t.marginLeft)+parseFloat(t.marginRight);return{width:e.offsetWidth+o,height:e.offsetHeight+n}}function E(e){var t={left:"right",right:"left",bottom:"top",top:"bottom"};return e.replace(/left|right|bottom|top/g,function(e){return t[e]})}function x(e,t,n){n=n.split("-")[0];var o=O(e),r={width:o.width,height:o.height},i=-1!==["right","left"].indexOf(n),f=i?"top":"left",s=i?"left":"top",a=i?"height":"width",p=i?"width":"height";return r[f]=t[f]+t[a]/2-o[a]/2,r[s]=n===s?t[s]-o[p]:t[E(s)],r}function L(e,t){return Array.prototype.find?e.find(t):e.filter(t)[0]}function T(t,n,o){return(void 0===o?t:t.slice(0,function(e,t,n){if(Array.prototype.findIndex)return e.findIndex(function(e){return e[t]===n});var o=L(e,function(e){return e[t]===n});return e.indexOf(o)}(t,"name",o))).forEach(function(t){t.function&&console.warn("`modifier.function` is deprecated, use `modifier.fn`!");var o=t.function||t.fn;t.enabled&&e(o)&&(n.offsets.popper=l(n.offsets.popper),n.offsets.reference=l(n.offsets.reference),n=o(n,t))}),n}function N(e,t){return e.some(function(e){var n=e.name;return e.enabled&&n===t})}function k(e){for(var t=[!1,"ms","Webkit","Moz","O"],n=e.charAt(0).toUpperCase()+e.slice(1),o=0;o<t.length-1;o++){var r=t[o],i=r?""+r+n:e;if(void 0!==window.document.body.style[i])return i}return null}function A(e,t,n,r){n.updateBound=r,window.addEventListener("resize",n.updateBound,{passive:!0});var i=o(e);return function e(t,n,r,i){var f="BODY"===t.nodeName,s=f?window:t;s.addEventListener(n,r,{passive:!0}),f||e(o(s.parentNode),n,r,i),i.push(s)}(i,"scroll",n.updateBound,n.scrollParents),n.scrollElement=i,n.eventsEnabled=!0,n}function B(){var e;this.state.eventsEnabled&&(window.cancelAnimationFrame(this.scheduleUpdate),this.state=(this.reference,e=this.state,window.removeEventListener("resize",e.updateBound),e.scrollParents.forEach(function(t){t.removeEventListener("scroll",e.updateBound)}),e.updateBound=null,e.scrollParents=[],e.scrollElement=null,e.eventsEnabled=!1,e))}function D(e){return""!==e&&!isNaN(parseFloat(e))&&isFinite(e)}function H(e,t){Object.keys(t).forEach(function(n){var o="";-1!==["width","height","top","right","bottom","left"].indexOf(n)&&D(t[n])&&(o="px"),e.style[n]=t[n]+o})}function C(e,t,n){var o=L(e,function(e){return e.name===t}),r=!!o&&e.some(function(e){return e.name===n&&e.enabled&&e.order<o.order});if(!r){var i="`"+t+"`";console.warn("`"+n+"` modifier is required by "+i+" modifier in order to work, be sure to include it before "+i+"!")}return r}function M(e){var t=1<arguments.length&&void 0!==arguments[1]&&arguments[1],n=Q.indexOf(e),o=Q.slice(n+1).concat(Q.slice(0,n));return t?o.reverse():o}function W(e,t,n,o){var r=[0,0],i=-1!==["right","left"].indexOf(o),f=e.split(/(\+|\-)/).map(function(e){return e.trim()}),s=f.indexOf(L(f,function(e){return-1!==e.search(/,|\s/)}));f[s]&&-1===f[s].indexOf(",")&&console.warn("Offsets separated by white space(s) are deprecated, use a comma (,) instead.");var a=/\s*,\s*|\s+/,p=-1===s?[f]:[f.slice(0,s).concat([f[s].split(a)[0]]),[f[s].split(a)[1]].concat(f.slice(s+1))];return(p=p.map(function(e,o){var r=(1===o?!i:i)?"height":"width",f=!1;return e.reduce(function(e,t){return""===e[e.length-1]&&-1!==["+","-"].indexOf(t)?(e[e.length-1]=t,f=!0,e):f?(e[e.length-1]+=t,f=!1,e):e.concat(t)},[]).map(function(e){return function(e,t,n,o){var r=e.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),i=+r[1],f=r[2];if(!i)return e;if(0===f.indexOf("%")){var s;switch(f){case"%p":s=n;break;case"%":case"%r":default:s=o}return l(s)[t]/100*i}return"vh"===f||"vw"===f?("vh"===f?S(document.documentElement.clientHeight,window.innerHeight||0):S(document.documentElement.clientWidth,window.innerWidth||0))/100*i:i}(e,r,t,n)})})).forEach(function(e,t){e.forEach(function(n,o){D(n)&&(r[t]+=n*("-"===e[o-1]?-1:1))})}),r}for(var P=Math.min,j=Math.floor,S=Math.max,F=["native code","[object MutationObserverConstructor]"],R="undefined"!=typeof window,U=["Edge","Trident","Firefox"],Y=0,I=0;I<U.length;I+=1)if(R&&0<=navigator.userAgent.indexOf(U[I])){Y=1;break}var q,z=R&&function(e){return F.some(function(t){return-1<(e||"").toString().indexOf(t)})}(window.MutationObserver)?function(e){var t=!1,n=0,o=document.createElement("span");return new MutationObserver(function(){e(),t=!1}).observe(o,{attributes:!0}),function(){t||(t=!0,o.setAttribute("x-index",n),++n)}}:function(e){var t=!1;return function(){t||(t=!0,setTimeout(function(){t=!1,e()},Y))}},G=function(){return null==q&&(q=-1!==navigator.appVersion.indexOf("MSIE 10")),q},V=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")},_=function(){function e(e,t){for(var n,o=0;o<t.length;o++)(n=t[o]).enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}return function(t,n,o){return n&&e(t.prototype,n),o&&e(t,o),t}}(),X=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e},J=Object.assign||function(e){for(var t,n=1;n<arguments.length;n++)for(var o in t=arguments[n])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e},K=["auto-start","auto","auto-end","top-start","top","top-end","right-start","right","right-end","bottom-end","bottom","bottom-start","left-end","left","left-start"],Q=K.slice(3),Z="flip",$="clockwise",ee="counterclockwise",te=function(){function t(n,o){var r=this,i=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{};V(this,t),this.scheduleUpdate=function(){return requestAnimationFrame(r.update)},this.update=z(this.update.bind(this)),this.options=J({},t.Defaults,i),this.state={isDestroyed:!1,isCreated:!1,scrollParents:[]},this.reference=n.jquery?n[0]:n,this.popper=o.jquery?o[0]:o,this.options.modifiers={},Object.keys(J({},t.Defaults.modifiers,i.modifiers)).forEach(function(e){r.options.modifiers[e]=J({},t.Defaults.modifiers[e]||{},i.modifiers?i.modifiers[e]:{})}),this.modifiers=Object.keys(this.options.modifiers).map(function(e){return J({name:e},r.options.modifiers[e])}).sort(function(e,t){return e.order-t.order}),this.modifiers.forEach(function(t){t.enabled&&e(t.onLoad)&&t.onLoad(r.reference,r.popper,r.options,t,r.state)}),this.update();var f=this.options.eventsEnabled;f&&this.enableEventListeners(),this.state.eventsEnabled=f}return _(t,[{key:"update",value:function(){return function(){if(!this.state.isDestroyed){var e={instance:this,styles:{},attributes:{},flipped:!1,offsets:{}};e.offsets.reference=y(this.state,this.popper,this.reference),e.placement=w(this.options.placement,e.offsets.reference,this.popper,this.reference,this.options.modifiers.flip.boundariesElement,this.options.modifiers.flip.padding),e.originalPlacement=e.placement,e.offsets.popper=x(this.popper,e.offsets.reference,e.placement),e.offsets.popper.position="absolute",e=T(this.modifiers,e),this.state.isCreated?this.options.onUpdate(e):(this.state.isCreated=!0,this.options.onCreate(e))}}.call(this)}},{key:"destroy",value:function(){return function(){return this.state.isDestroyed=!0,N(this.modifiers,"applyStyle")&&(this.popper.removeAttribute("x-placement"),this.popper.style.left="",this.popper.style.position="",this.popper.style.top="",this.popper.style[k("transform")]=""),this.disableEventListeners(),this.options.removeOnDestroy&&this.popper.parentNode.removeChild(this.popper),this}.call(this)}},{key:"enableEventListeners",value:function(){return function(){this.state.eventsEnabled||(this.state=A(this.reference,this.options,this.state,this.scheduleUpdate))}.call(this)}},{key:"disableEventListeners",value:function(){return B.call(this)}}]),t}();return te.Utils=("undefined"==typeof window?global:window).PopperUtils,te.placements=K,te.Defaults={placement:"bottom",eventsEnabled:!0,removeOnDestroy:!1,onCreate:function(){},onUpdate:function(){},modifiers:{shift:{order:100,enabled:!0,fn:function(e){var t=e.placement,n=t.split("-")[0],o=t.split("-")[1];if(o){var r=e.offsets,i=r.reference,f=r.popper,s=-1!==["bottom","top"].indexOf(n),a=s?"left":"top",p=s?"width":"height",u={start:X({},a,i[a]),end:X({},a,i[a]+i[p]-f[p])};e.offsets.popper=J({},f,u[o])}return e}},offset:{order:200,enabled:!0,fn:function(e,t){var n,o=t.offset,r=e.placement,i=e.offsets,f=i.popper,s=i.reference,a=r.split("-")[0];return n=D(+o)?[+o,0]:W(o,f,s,a),"left"===a?(f.top+=n[0],f.left-=n[1]):"right"===a?(f.top+=n[0],f.left+=n[1]):"top"===a?(f.left+=n[0],f.top-=n[1]):"bottom"===a&&(f.left+=n[0],f.top+=n[1]),e.popper=f,e},offset:0},preventOverflow:{order:300,enabled:!0,fn:function(e,t){var n=t.boundariesElement||r(e.instance.popper);e.instance.reference===n&&(n=r(n));var o=v(e.instance.popper,e.instance.reference,t.padding,n);t.boundaries=o;var i=t.priority,f=e.offsets.popper,s={primary:function(e){var n=f[e];return f[e]<o[e]&&!t.escapeWithReference&&(n=S(f[e],o[e])),X({},e,n)},secondary:function(e){var n="right"===e?"left":"top",r=f[n];return f[e]>o[e]&&!t.escapeWithReference&&(r=P(f[n],o[e]-("right"===e?f.width:f.height))),X({},n,r)}};return i.forEach(function(e){var t=-1===["left","top"].indexOf(e)?"secondary":"primary";f=J({},f,s[t](e))}),e.offsets.popper=f,e},priority:["left","right","top","bottom"],padding:5,boundariesElement:"scrollParent"},keepTogether:{order:400,enabled:!0,fn:function(e){var t=e.offsets,n=t.popper,o=t.reference,r=e.placement.split("-")[0],i=j,f=-1!==["top","bottom"].indexOf(r),s=f?"right":"bottom",a=f?"left":"top",p=f?"width":"height";return n[s]<i(o[a])&&(e.offsets.popper[a]=i(o[a])-n[p]),n[a]>i(o[s])&&(e.offsets.popper[a]=i(o[s])),e}},arrow:{order:500,enabled:!0,fn:function(e,t){if(!C(e.instance.modifiers,"arrow","keepTogether"))return e;var n=t.element;if("string"==typeof n){if(!(n=e.instance.popper.querySelector(n)))return e}else if(!e.instance.popper.contains(n))return console.warn("WARNING: `arrow.element` must be child of its popper element!"),e;var o=e.placement.split("-")[0],r=e.offsets,i=r.popper,f=r.reference,s=-1!==["left","right"].indexOf(o),a=s?"height":"width",p=s?"top":"left",u=s?"left":"top",d=s?"bottom":"right",c=O(n)[a];f[d]-c<i[p]&&(e.offsets.popper[p]-=i[p]-(f[d]-c)),f[p]+c>i[d]&&(e.offsets.popper[p]+=f[p]+c-i[d]);var h=f[p]+f[a]/2-c/2-l(e.offsets.popper)[p];return h=S(P(i[a]-c,h),0),e.arrowElement=n,e.offsets.arrow={},e.offsets.arrow[p]=Math.round(h),e.offsets.arrow[u]="",e},element:"[x-arrow]"},flip:{order:600,enabled:!0,fn:function(e,t){if(N(e.instance.modifiers,"inner"))return e;if(e.flipped&&e.placement===e.originalPlacement)return e;var n=v(e.instance.popper,e.instance.reference,t.padding,t.boundariesElement),o=e.placement.split("-")[0],r=E(o),i=e.placement.split("-")[1]||"",f=[];switch(t.behavior){case Z:f=[o,r];break;case $:f=M(o);break;case ee:f=M(o,!0);break;default:f=t.behavior}return f.forEach(function(s,a){if(o!==s||f.length===a+1)return e;o=e.placement.split("-")[0],r=E(o);var p=e.offsets.popper,u=e.offsets.reference,d=j,l="left"===o&&d(p.right)>d(u.left)||"right"===o&&d(p.left)<d(u.right)||"top"===o&&d(p.bottom)>d(u.top)||"bottom"===o&&d(p.top)<d(u.bottom),c=d(p.left)<d(n.left),h=d(p.right)>d(n.right),m=d(p.top)<d(n.top),g=d(p.bottom)>d(n.bottom),v="left"===o&&c||"right"===o&&h||"top"===o&&m||"bottom"===o&&g,b=-1!==["top","bottom"].indexOf(o),w=!!t.flipVariations&&(b&&"start"===i&&c||b&&"end"===i&&h||!b&&"start"===i&&m||!b&&"end"===i&&g);(l||v||w)&&(e.flipped=!0,(l||v)&&(o=f[a+1]),w&&(i=function(e){return"end"===e?"start":"start"===e?"end":e}(i)),e.placement=o+(i?"-"+i:""),e.offsets.popper=J({},e.offsets.popper,x(e.instance.popper,e.offsets.reference,e.placement)),e=T(e.instance.modifiers,e,"flip"))}),e},behavior:"flip",padding:5,boundariesElement:"viewport"},inner:{order:700,enabled:!1,fn:function(e){var t=e.placement,n=t.split("-")[0],o=e.offsets,r=o.popper,i=o.reference,f=-1!==["left","right"].indexOf(n),s=-1===["top","left"].indexOf(n);return r[f?"left":"top"]=i[t]-(s?r[f?"width":"height"]:0),e.placement=E(t),e.offsets.popper=l(r),e}},hide:{order:800,enabled:!0,fn:function(e){if(!C(e.instance.modifiers,"hide","preventOverflow"))return e;var t=e.offsets.reference,n=L(e.instance.modifiers,function(e){return"preventOverflow"===e.name}).boundaries;if(t.bottom<n.top||t.left>n.right||t.top>n.bottom||t.right<n.left){if(!0===e.hide)return e;e.hide=!0,e.attributes["x-out-of-boundaries"]=""}else{if(!1===e.hide)return e;e.hide=!1,e.attributes["x-out-of-boundaries"]=!1}return e}},computeStyle:{order:850,enabled:!0,fn:function(e,t){var n=t.x,o=t.y,i=e.offsets.popper,f=L(e.instance.modifiers,function(e){return"applyStyle"===e.name}).gpuAcceleration;void 0!==f&&console.warn("WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!");var s,a,p=void 0===f?t.gpuAcceleration:f,u=c(r(e.instance.popper)),d={position:i.position},l={left:j(i.left),top:j(i.top),bottom:j(i.bottom),right:j(i.right)},h="bottom"===n?"top":"bottom",m="right"===o?"left":"right",g=k("transform");if(a="bottom"==h?-u.height+l.bottom:l.top,s="right"==m?-u.width+l.right:l.left,p&&g)d[g]="translate3d("+s+"px, "+a+"px, 0)",d[h]=0,d[m]=0,d.willChange="transform";else{var v="bottom"==h?-1:1,b="right"==m?-1:1;d[h]=a*v,d[m]=s*b,d.willChange=h+", "+m}var w={"x-placement":e.placement};return e.attributes=J({},w,e.attributes),e.styles=J({},d,e.styles),e},gpuAcceleration:!0,x:"bottom",y:"right"},applyStyle:{order:900,enabled:!0,fn:function(e){return H(e.instance.popper,e.styles),function(e,t){Object.keys(t).forEach(function(n){!1===t[n]?e.removeAttribute(n):e.setAttribute(n,t[n])})}(e.instance.popper,e.attributes),e.offsets.arrow&&H(e.arrowElement,e.offsets.arrow),e},onLoad:function(e,t,n,o,r){var i=y(0,t,e),f=w(n.placement,i,t,e,n.modifiers.flip.boundariesElement,n.modifiers.flip.padding);return t.setAttribute("x-placement",f),H(t,{position:"absolute"}),n},gpuAcceleration:void 0}}},te});
