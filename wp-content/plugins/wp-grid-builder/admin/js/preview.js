/*!
* WP Grid Builder
*
* @package   WP Grid Builder
* @author    Loïc Blascos
* @link      https://www.wpgridbuilder.com
* @copyright 2019-2025 Loïc Blascos
*
*/
(()=>{const e=(e,t)=>{const a=new CustomEvent(e,{detail:t});window.parent.dispatchEvent(a)};addEventListener("DOMContentLoaded",(()=>{requestAnimationFrame((()=>setTimeout((()=>e("wpgb.preview.loaded")))))})),addEventListener("wpgb.save.object",(e=>{let{detail:{id:t,object:a,settings:i}}=e;document.querySelector(`.wpgb-preview-iframe__edit-button[data-id="${CSS.escape(t)}"][data-object="${CSS.escape(a)}"]`)?.setAttribute?.("data-metadata",JSON.stringify(i))})),addEventListener("click",(t=>{(t=>{const a=t?.target?.closest?.(".wpgb-preview-iframe__edit-button");a&&e("wpgb.preview.edit",a.dataset)})(t),(e=>{const t=e.target;"A"===t?.tagName&&(t?.closest(".wpgb-lightbox, .wpgb-error-msg, .wpgb-page")||(e.preventDefault(),e.stopPropagation(),e.stopImmediatePropagation()))})(t)}))})();