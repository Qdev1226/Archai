!function(){"use strict";var e,t={423:function(){var e,t=window.wp.blocks,o=window.React;function n(){return n=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var o=arguments[t];for(var n in o)Object.prototype.hasOwnProperty.call(o,n)&&(e[n]=o[n])}return e},n.apply(this,arguments)}var l=function(t){return o.createElement("svg",n({width:24,height:24,style:{color:"red"}},t),e||(e=o.createElement("path",{d:"M21.8 8s-.195-1.377-.795-1.984c-.76-.797-1.613-.8-2.004-.847-2.798-.203-6.996-.203-6.996-.203h-.01s-4.197 0-6.996.202c-.39.046-1.242.05-2.003.846C2.395 6.623 2.2 8 2.2 8S2 9.62 2 11.24v1.517c0 1.618.2 3.237.2 3.237s.195 1.378.795 1.985c.76.797 1.76.77 2.205.855 1.6.153 6.8.2 6.8.2s4.203-.005 7-.208c.392-.047 1.244-.05 2.005-.847.6-.607.795-1.985.795-1.985s.2-1.618.2-3.237V11.24C22 9.62 21.8 8 21.8 8zM9.935 14.595v-5.62l5.403 2.82-5.403 2.8z"})))},r=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"boldblocks/youtube-block","title":"Better Youtube Video Block","category":"media","description":"Embed Youtube video without slowing down your site.","keywords":["youtube","video","embed","insert"],"attributes":{"url":{"type":"string"},"caption":{"type":"string","source":"html","selector":"figcaption"}},"supports":{"align":true},"textdomain":"better-youtube-embed-block","editorScript":"file:./index.js","viewScript":"file:./frontend.js","editorStyle":"file:./index.css","style":"file:./style-index.css"}'),a=window.wp.element,c=window.wp.i18n,i=window.wp.blockEditor,u=window.wp.components,s=e=>{let{icon:t,label:o,value:n,onChange:l,onSubmit:r,isInvalidURL:s=!1}=e;const b=(0,c.__)("Input a YouTube video URL or an ID","better-youtube-embed-block");return(0,a.createElement)(u.Placeholder,{icon:(0,a.createElement)(i.BlockIcon,{icon:t,showColors:!0}),label:o,className:"boldblocks-youtube",instructions:b},(0,a.createElement)("form",{className:"boldblocks-youtube__form",onSubmit:r,style:{gap:"8px"}},(0,a.createElement)("input",{type:"text",value:n||"",className:"components-placeholder__input","aria-label":o,placeholder:b,onChange:l,style:{flex:"1 1 auto"}}),(0,a.createElement)(u.Button,{variant:"primary",type:"submit",className:"components-placeholder__submit"},(0,c._x)("Embed","Embed button label","better-youtube-embed-block"))),s&&(0,a.createElement)("div",{className:"components-placeholder__error"},(0,a.createElement)("div",{className:"components-placeholder__instructions"},b)))},b=window.wp.primitives,m=(0,a.createElement)(b.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(b.Path,{d:"M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z"})),p=(0,a.createElement)(b.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)(b.Path,{fillRule:"evenodd",clipRule:"evenodd",d:"M6 5.5h12a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5H6a.5.5 0 0 1-.5-.5V6a.5.5 0 0 1 .5-.5ZM4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6Zm4 10h2v-1.5H8V16Zm5 0h-2v-1.5h2V16Zm1 0h2v-1.5h-2V16Z"})),d=e=>{let{showEditButton:t,switchBackToURLInput:o,showFetchCaption:n,fetchCaption:l,url:r}=e;return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(i.BlockControls,null,(0,a.createElement)(u.ToolbarGroup,null,t&&(0,a.createElement)(u.ToolbarButton,{className:"components-toolbar__control",label:(0,c.__)("Edit URL/ID","better-youtube-embed-block"),icon:m,onClick:o}),n&&(0,a.createElement)(u.ToolbarButton,{className:"components-toolbar__control",label:(0,c.__)("Fetch the video title as the caption","better-youtube-embed-block"),icon:p,onClick:()=>l(r)}))))},h=e=>{let{id:t="",playLabel:o}=e;return(0,a.createElement)("div",{id:`yb-video-${t}`,className:"yb-player","data-video-id":t,"data-title":o,style:{backgroundImage:`url(https://img.youtube.com/vi/${t}/hqdefault.jpg)`}},(0,a.createElement)("button",{type:"button",className:"yb-btn-play"},(0,a.createElement)("span",{className:"visually-hidden"},o)))},v="figure";function y(e){var t;if(/^[a-zA-Z0-9_-]{11}$/.test(e))return e;const o=/(youtu.*be.*)\/(watch\?v=|embed\/|v|shorts|)(.*?((?=[&#?])|$))/gm.exec(e);return o&&null!==(t=o[3])&&void 0!==t?t:""}const{name:f}=r;var w={from:[{type:"block",blocks:["core/embed"],isMatch:e=>{let{providerNameSlug:t}=e;return"youtube"===t},transform:e=>{let{url:o,caption:n}=e;return(0,t.createBlock)(f,{url:o,caption:n})}}],to:[{type:"block",blocks:["core/embed"],transform:e=>{let{url:o,caption:n}=e;return o=o&&11===o.length?`https://youtu.be/${o}`:o,(0,t.createBlock)("core/embed",{url:o,caption:n,providerNameSlug:"youtube",responsive:!0})}}]};(0,t.registerBlockType)(r,{icon:l,transforms:w,edit:function(e){const{attributes:{url:t="",caption:o},isSelected:n,setAttributes:r,onFocus:b}=e,[m,p]=(0,a.useState)(t),f=y(t),w=y(m),[g,E]=(0,a.useState)(!1),k=!f||g,[_,x]=(0,a.useState)(!1),B=e=>{e&&(11===e.length&&(e=`https://youtu.be/${e}`),fetch(`https://www.youtube.com/oembed?format=json&url=${e}`).then((e=>!!e.ok&&e.json())).then((e=>{e&&null!=e&&e.title&&r({caption:e.title})})).finally((()=>x(!1))))},N=(0,i.useBlockProps)();return k?(0,a.createElement)(v,N,(0,a.createElement)(s,{icon:l,label:(0,c.__)("Youtube URL or Youtube Video ID","better-youtube-embed-block"),onFocus:b,value:m,isInvalidURL:!w&&m&&(null==m?void 0:m.length)>5,onSubmit:e=>{e&&e.preventDefault(),E(!1),(e=>{!o&&w&&f!==w&&(x(!0),B(e)),r({url:e})})(m)},onChange:e=>{p(e.target.value)}})):(0,a.createElement)(a.Fragment,null,n&&(0,a.createElement)(d,{showEditButton:f,switchBackToURLInput:()=>E(!0),showFetchCaption:!!y(t),url:t,fetchCaption:B}),(0,a.createElement)(v,N,!k&&f&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h,{id:f,playLabel:(0,c.__)("Play","better-youtube-embed-block")}),_?(0,a.createElement)(u.Spinner,null):(0,a.createElement)(i.RichText,{tagName:"figcaption",className:"yb-caption",placeholder:(0,c.__)("Add caption","better-youtube-embed-block"),value:o,onChange:e=>r({caption:e}),inlineToolbar:!0}))))},save:function(e){const{attributes:{url:t,caption:o}}=e,n=y(t);return(0,a.createElement)(v,i.useBlockProps.save(),n&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h,{id:n,playLabel:"Play"}),!i.RichText.isEmpty(o)&&(0,a.createElement)(i.RichText.Content,{className:"yb-caption",tagName:"figcaption",value:o})))}})}},o={};function n(e){var l=o[e];if(void 0!==l)return l.exports;var r=o[e]={exports:{}};return t[e](r,r.exports,n),r.exports}n.m=t,e=[],n.O=function(t,o,l,r){if(!o){var a=1/0;for(s=0;s<e.length;s++){o=e[s][0],l=e[s][1],r=e[s][2];for(var c=!0,i=0;i<o.length;i++)(!1&r||a>=r)&&Object.keys(n.O).every((function(e){return n.O[e](o[i])}))?o.splice(i--,1):(c=!1,r<a&&(a=r));if(c){e.splice(s--,1);var u=l();void 0!==u&&(t=u)}}return t}r=r||0;for(var s=e.length;s>0&&e[s-1][2]>r;s--)e[s]=e[s-1];e[s]=[o,l,r]},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={826:0,431:0};n.O.j=function(t){return 0===e[t]};var t=function(t,o){var l,r,a=o[0],c=o[1],i=o[2],u=0;if(a.some((function(t){return 0!==e[t]}))){for(l in c)n.o(c,l)&&(n.m[l]=c[l]);if(i)var s=i(n)}for(t&&t(o);u<a.length;u++)r=a[u],n.o(e,r)&&e[r]&&e[r][0](),e[r]=0;return n.O(s)},o=self.webpackChunkyoutube_block=self.webpackChunkyoutube_block||[];o.forEach(t.bind(null,0)),o.push=t.bind(null,o.push.bind(o))}();var l=n.O(void 0,[431],(function(){return n(423)}));l=n.O(l)}();