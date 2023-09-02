(()=>{var e,t={332:(e,t,r)=>{"use strict";const l=window.wp.element,n=window.wp.blocks,i=window.React;var o=r(913),s=r.n(o);const c=window.wp.i18n,a=window.wp.blockEditor,u=window.wp.components,d=window.wp.data;function p(e){const t=(0,d.select)("core/block-editor").getBlock(e),r=(0,d.select)("core/block-editor").getBlockCount(e),l=t?.innerBlocks?.length;l>0&&r!==l&&(0,d.dispatch)("core/block-editor").updateBlockAttributes(e,{items:l})}(0,n.registerBlockType)("lez-library/listitem",{title:(0,c.__)("List Item","listicles"),parent:["lez-library/listicles"],icon:"editor-rtl",category:"layout",description:(0,c.__)("An individual list item.","listicles"),edit:function(e){const{clientId:t}=e;return(0,i.useEffect)((()=>{const e=(0,d.select)("core/block-editor").getBlockHierarchyRootClientId(t);return p(e),()=>p(e)}),[t]),(0,l.createElement)(l.Fragment,null,(0,l.createElement)("div",{className:"listicles-innerblocks"},(0,l.createElement)(a.InnerBlocks,{template:[["lez-library/listdt"],["lez-library/listdd"]],allowedBlocks:[["lez-library/listdt"],["lez-library/listdd"]],templateLock:"all"})))},save:function(){return(0,l.createElement)(a.InnerBlocks.Content,null)}}),(0,n.registerBlockType)("lez-library/listdt",{title:(0,c.__)("Listicle Item Title","listicles"),parent:["lez-library/listitem"],icon:"migrate",category:"layout",attributes:{content:{type:"string",source:"html",selector:"dt",default:(0,c.__)("Title","listicles")},placeholder:{type:"string",default:(0,c.__)("Title","listicles")}},description:(0,c.__)("An individual list item title.","listicles"),edit({attributes:e,setAttributes:t,className:r}){const{content:n}=e;return(0,l.createElement)(l.Fragment,null,(0,l.createElement)(a.RichText,{tagName:"dt",className:r,value:n,allowedFormats:["core/bold","core/link","core/italic","core/strikethrough","core/text-color","yoast-seo/link"],onChange:e=>t({content:e})}))},save({attributes:e,className:t}){const{content:r}=e;return(0,l.createElement)(a.RichText.Content,{tagName:"dt",className:t,value:r})}}),(0,n.registerBlockType)("lez-library/listdd",{title:(0,c.__)("List Item Content","listicles"),parent:["lez-library/listitem"],icon:"migrate",category:"layout",description:(0,c.__)("A list item description (aka content).","listicles"),edit:function(e){const{className:t}=e;return(0,l.createElement)(l.Fragment,null,(0,l.createElement)("dd",{className:t},(0,l.createElement)(a.InnerBlocks,{template:[["core/paragraph",{placeholder:"List Content..."}]],templateLock:!1})))},save:function(e){const{attributes:{className:t}}=e;return(0,l.createElement)("dd",{className:t},(0,l.createElement)(a.InnerBlocks.Content,null))}});const m=function(e,t){var r,l,n=0;function i(){var i,o,s=r,c=arguments.length;e:for(;s;){if(s.args.length===arguments.length){for(o=0;o<c;o++)if(s.args[o]!==arguments[o]){s=s.next;continue e}return s!==r&&(s===l&&(l=s.prev),s.prev.next=s.next,s.next&&(s.next.prev=s.prev),s.next=r,s.prev=null,r.prev=s,r=s),s.val}s=s.next}for(i=new Array(c),o=0;o<c;o++)i[o]=arguments[o];return s={args:i,val:e.apply(null,i)},r?(r.prev=s,s.next=r):l=s,n===t.maxSize?(l=l.prev).next=null:n++,r=s,s.val}return t=t||{},i.clear=function(){r=null,l=null,n=0},i}((e=>s()(e,(()=>["lez-library/listitem"]))));(0,n.registerBlockType)("lez-library/listicles",{title:(0,c.__)("Listicle","listicles"),icon:"excerpt-view",category:"layout",attributes:{items:{type:"number",default:2},reversed:{type:"boolean",default:!1}},description:(0,c.__)("A block for listicles. You can add items, remove them, and flip them in reverse.","listicles"),edit:e=>{const{attributes:{className:t},setAttributes:r,clientId:n}=e;let{items:o,reversed:s}=e.attributes;(0,i.useEffect)((()=>p(n)),[n]);let d="",f="0";return s&&(d="reversed",f=parseInt(`${o}`)+1),(0,l.createElement)(l.Fragment,null,(0,l.createElement)(a.InspectorControls,null,(0,l.createElement)(u.PanelBody,{title:(0,c.__)("Listicle Settings","listicles")},(0,l.createElement)(u.ToggleControl,{label:"Reversed",help:e=>e?(0,c.__)("Reversed order (10 - 1)","listicles"):(0,c.__)("Numerical order (1 - 10)","listicles"),checked:e.attributes.reversed,onChange:()=>e.setAttributes({reversed:!e.attributes.reversed})}))),(0,l.createElement)("dl",{className:`${t} ${d} listicle items-${o}`,style:{counterReset:`listicle-counter ${f}`}},(0,l.createElement)(a.InnerBlocks,{template:m(o),allowedBlocks:[["lez-library/listitem"]],defaultBlock:"lez-library/listitem"}),(0,l.createElement)("div",{className:"listicles-buttons"},(0,l.createElement)(u.Button,{icon:"insert",onClick:()=>{const e=wp.blocks.createBlock("lez-library/listitem");wp.data.dispatch("core/block-editor").insertBlock(e,o,n)},className:"editor-inserter__toggle"},"Add List Item"),(0,l.createElement)(u.Button,{icon:"controls-repeat",onClick:()=>r({reversed:!s}),className:"editor-inserter__toggle"},"Toggle List Order"))))},save:e=>{const{attributes:{className:t}}=e;let{items:r,reversed:n}=e.attributes,i="",o=0;return n&&(i="reversed",o=parseInt(`${r}`)+1),(0,l.createElement)("dl",{className:`${t} ${i} listicle items-${r}`,style:{counterReset:`listicle-counter ${o}`}},(0,l.createElement)(a.InnerBlocks.Content,null))}})},705:(e,t,r)=>{var l=r(639).Symbol;e.exports=l},239:(e,t,r)=>{var l=r(705),n=r(607),i=r(333),o=l?l.toStringTag:void 0;e.exports=function(e){return null==e?void 0===e?"[object Undefined]":"[object Null]":o&&o in Object(e)?n(e):i(e)}},545:e=>{e.exports=function(e,t){for(var r=-1,l=Array(e);++r<e;)l[r]=t(r);return l}},561:(e,t,r)=>{var l=r(990),n=/^\s+/;e.exports=function(e){return e?e.slice(0,l(e)+1).replace(n,""):e}},290:(e,t,r)=>{var l=r(557);e.exports=function(e){return"function"==typeof e?e:l}},957:(e,t,r)=>{var l="object"==typeof r.g&&r.g&&r.g.Object===Object&&r.g;e.exports=l},607:(e,t,r)=>{var l=r(705),n=Object.prototype,i=n.hasOwnProperty,o=n.toString,s=l?l.toStringTag:void 0;e.exports=function(e){var t=i.call(e,s),r=e[s];try{e[s]=void 0;var l=!0}catch(e){}var n=o.call(e);return l&&(t?e[s]=r:delete e[s]),n}},333:e=>{var t=Object.prototype.toString;e.exports=function(e){return t.call(e)}},639:(e,t,r)=>{var l=r(957),n="object"==typeof self&&self&&self.Object===Object&&self,i=l||n||Function("return this")();e.exports=i},990:e=>{var t=/\s/;e.exports=function(e){for(var r=e.length;r--&&t.test(e.charAt(r)););return r}},557:e=>{e.exports=function(e){return e}},218:e=>{e.exports=function(e){var t=typeof e;return null!=e&&("object"==t||"function"==t)}},5:e=>{e.exports=function(e){return null!=e&&"object"==typeof e}},448:(e,t,r)=>{var l=r(239),n=r(5);e.exports=function(e){return"symbol"==typeof e||n(e)&&"[object Symbol]"==l(e)}},913:(e,t,r)=>{var l=r(545),n=r(290),i=r(554),o=4294967295,s=Math.min;e.exports=function(e,t){if((e=i(e))<1||e>9007199254740991)return[];var r=o,c=s(e,o);t=n(t),e-=o;for(var a=l(c,t);++r<e;)t(r);return a}},601:(e,t,r)=>{var l=r(841);e.exports=function(e){return e?Infinity===(e=l(e))||e===-1/0?17976931348623157e292*(e<0?-1:1):e==e?e:0:0===e?e:0}},554:(e,t,r)=>{var l=r(601);e.exports=function(e){var t=l(e),r=t%1;return t==t?r?t-r:t:0}},841:(e,t,r)=>{var l=r(561),n=r(218),i=r(448),o=/^[-+]0x[0-9a-f]+$/i,s=/^0b[01]+$/i,c=/^0o[0-7]+$/i,a=parseInt;e.exports=function(e){if("number"==typeof e)return e;if(i(e))return NaN;if(n(e)){var t="function"==typeof e.valueOf?e.valueOf():e;e=n(t)?t+"":t}if("string"!=typeof e)return 0===e?e:+e;e=l(e);var r=s.test(e);return r||c.test(e)?a(e.slice(2),r?2:8):o.test(e)?NaN:+e}}},r={};function l(e){var n=r[e];if(void 0!==n)return n.exports;var i=r[e]={exports:{}};return t[e](i,i.exports,l),i.exports}l.m=t,e=[],l.O=(t,r,n,i)=>{if(!r){var o=1/0;for(u=0;u<e.length;u++){r=e[u][0],n=e[u][1],i=e[u][2];for(var s=!0,c=0;c<r.length;c++)(!1&i||o>=i)&&Object.keys(l.O).every((e=>l.O[e](r[c])))?r.splice(c--,1):(s=!1,i<o&&(o=i));if(s){e.splice(u--,1);var a=n();void 0!==a&&(t=a)}}return t}i=i||0;for(var u=e.length;u>0&&e[u-1][2]>i;u--)e[u]=e[u-1];e[u]=[r,n,i]},l.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return l.d(t,{a:t}),t},l.d=(e,t)=>{for(var r in t)l.o(t,r)&&!l.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},l.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),l.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={826:0,431:0};l.O.j=t=>0===e[t];var t=(t,r)=>{var n,i,o=r[0],s=r[1],c=r[2],a=0;if(o.some((t=>0!==e[t]))){for(n in s)l.o(s,n)&&(l.m[n]=s[n]);if(c)var u=c(l)}for(t&&t(r);a<o.length;a++)i=o[a],l.o(e,i)&&e[i]&&e[i][0](),e[i]=0;return l.O(u)},r=self.webpackChunklisticles=self.webpackChunklisticles||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))})();var n=l.O(void 0,[431],(()=>l(332)));n=l.O(n)})();