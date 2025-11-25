(function(){const v=document.querySelector("[data-tenant-slug]")?.dataset.tenantSlug||window.location.pathname.split("/")[1]||"",u=`/${v}/api/onboardings`;let n={onboardings:[],filteredOnboardings:[],selectedIds:new Set,currentPage:1,pageSize:10,total:0,sortBy:"joiningDate",sortDir:"asc",filters:{search:"",status:"",department:"",manager:"",joiningMonth:""},isLoading:!1};function h(){try{console.log("Employee Onboarding script initializing..."),console.log("DEBUG: tenantSlug:",v),console.log("DEBUG: API_BASE:",u),L(),l()}catch(e){console.error("ERROR: Failed to initialize Employee Onboarding script:",e),console.error("ERROR Stack:",e.stack)}}document.readyState==="loading"?document.addEventListener("DOMContentLoaded",h):setTimeout(h,0),window.addEventListener("load",function(){n.onboardings.length===0&&(console.log("Window loaded, onboardings still empty, calling loadOnboardings"),l())});function L(){const e=document.getElementById("search-input");if(e){let o;e.addEventListener("input",function(r){clearTimeout(o),o=setTimeout(()=>{n.filters.search=r.target.value,n.currentPage=1,l()},300)})}["department","manager","status","joining-month"].forEach(o=>{const r=document.getElementById(`filter-${o}`);r&&r.addEventListener("change",function(i){const m=o.replace("-","");n.filters[m]=i.target.value,n.currentPage=1,l()})}),document.querySelectorAll(".sort-btn").forEach(o=>{o.addEventListener("click",function(){const r=this.dataset.sort;n.sortBy===r?n.sortDir=n.sortDir==="asc"?"desc":"asc":(n.sortBy=r,n.sortDir="asc"),l()})});const t=document.getElementById("select-all-checkbox");t&&t.addEventListener("change",function(o){const r=o.target.checked;n.selectedIds.clear(),r&&n.filteredOnboardings.forEach(i=>{n.selectedIds.add(i.id)}),y(),f()}),document.getElementById("bulk-send-reminder")?.addEventListener("click",O),document.getElementById("bulk-mark-complete")?.addEventListener("click",T),document.getElementById("bulk-export-selected")?.addEventListener("click",M),document.getElementById("start-onboarding-btn")?.addEventListener("click",()=>{c("Start Onboarding modal will open here","info")}),document.getElementById("start-onboarding-empty")?.addEventListener("click",()=>{c("Start Onboarding modal will open here","info")});const a=document.getElementById("import-candidates-btn");a?(console.log("Import button found, attaching listener"),a.addEventListener("click",function(o){o.preventDefault(),console.log("Import button clicked"),z()})):console.error("Import candidates button not found"),document.getElementById("export-csv-btn")?.addEventListener("click",D),document.getElementById("close-slide-over")?.addEventListener("click",b),document.getElementById("slide-over-backdrop")?.addEventListener("click",b),document.querySelectorAll(".tab-btn").forEach(o=>{o.addEventListener("click",function(){E(this.dataset.tab)})}),document.addEventListener("keydown",function(o){o.key==="Escape"&&b()})}async function l(){n.isLoading=!0,q();try{const e=new URLSearchParams({page:n.currentPage,per_page:n.pageSize,sortBy:n.sortBy,sortDir:n.sortDir,...Object.fromEntries(Object.entries(n.filters).filter(([r,i])=>i!==""))}),t=`${u}?${e}`,a=await fetch(t,{method:"GET",headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest"},credentials:"same-origin"});if(!a.ok)throw new Error(`Failed to load onboardings: ${a.status}`);const o=await a.json();n.onboardings=o.data||[],n.filteredOnboardings=o.data||[],n.total=o.meta?.total||0,k(),f(),H()}catch(e){console.error("Error loading onboardings:",e),k(),p("Failed to load onboardings. Please try again.")}finally{n.isLoading=!1}}function f(){const e=document.getElementById("onboardings-tbody"),t=document.getElementById("mobile-cards"),a=document.getElementById("empty-state");if(!(!e||!t)){if(n.filteredOnboardings.length===0){e.innerHTML="",t.innerHTML="",a?.classList.remove("hidden");return}a?.classList.add("hidden"),e.innerHTML=n.filteredOnboardings.map(o=>S(o)).join(""),t.innerHTML=n.filteredOnboardings.map(o=>B(o)).join(""),P()}}function S(e){const t=$(e.fullName),a=I(e.joiningDate),o=n.selectedIds.has(e.id),r=e.progressPercent>=100;return`
            <tr class="hover:bg-gray-50" tabindex="0" data-id="${e.id}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" 
                           class="row-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                           data-id="${e.id}"
                           ${o?"checked":""}>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            ${e.avatarUrl?`<img class="h-10 w-10 rounded-full" src="${e.avatarUrl}" alt="${e.fullName}">`:`<div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-medium text-sm">${t}</div>`}
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${s(e.fullName)}</div>
                            <div class="text-sm text-gray-500">${s(e.designation)}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="mailto:${e.email}" class="text-sm text-blue-600 hover:text-blue-800">${s(e.email)}</a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${s(e.designation)} · ${s(e.department)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${a}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: ${e.progressPercent}%"
                                 role="progressbar" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100" 
                                 aria-valuenow="${e.progressPercent}"></div>
                        </div>
                        <span class="text-sm text-gray-700">${e.progressPercent}%</span>
                    </div>
                    ${e.pendingItems>0?`<div class="text-xs text-gray-500 mt-1">${e.pendingItems} pending</div>`:""}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${x(e.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <button type="button" 
                                class="action-btn text-blue-600 hover:text-blue-800"
                                data-action="view"
                                data-id="${e.id}"
                                aria-label="View onboarding for ${s(e.fullName)}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button type="button" 
                                class="action-btn text-gray-600 hover:text-gray-800"
                                data-action="edit"
                                data-id="${e.id}"
                                aria-label="Edit onboarding for ${s(e.fullName)}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button type="button" 
                                class="action-btn ${r?"bg-purple-600 text-white hover:bg-purple-700":"bg-gray-300 text-gray-500 cursor-not-allowed"} px-3 py-1 rounded text-sm font-medium"
                                data-action="convert"
                                data-id="${e.id}"
                                ${r?"":'disabled title="Convert to employee (complete pending items first)"'}
                                aria-label="Convert to employee for ${s(e.fullName)}">
                            Convert
                        </button>
                    </div>
                </td>
            </tr>
        `}function B(e){const t=$(e.fullName),a=I(e.joiningDate),o=n.selectedIds.has(e.id),r=e.progressPercent>=100;return`
            <div class="bg-white border border-gray-200 rounded-lg p-4" data-id="${e.id}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               class="row-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                               data-id="${e.id}"
                               ${o?"checked":""}>
                        ${e.avatarUrl?`<img class="h-10 w-10 rounded-full" src="${e.avatarUrl}" alt="${e.fullName}">`:`<div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-medium text-sm">${t}</div>`}
                        <div>
                            <div class="font-medium text-gray-900">${s(e.fullName)}</div>
                            <div class="text-sm text-gray-500">${s(e.designation)} · ${s(e.department)}</div>
                        </div>
                    </div>
                    ${x(e.status)}
                </div>
                <div class="space-y-2 text-sm text-gray-600 mb-3">
                    <div>${a} · ${e.progressPercent}% completed</div>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: ${e.progressPercent}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-2">
                    <button type="button" class="action-btn text-blue-600" data-action="view" data-id="${e.id}">View</button>
                    <button type="button" class="action-btn text-gray-600" data-action="edit" data-id="${e.id}">Edit</button>
                    <button type="button" 
                            class="action-btn ${r?"bg-purple-600 text-white":"bg-gray-300 text-gray-500"} px-3 py-1 rounded text-sm"
                            data-action="convert"
                            data-id="${e.id}"
                            ${r?"":"disabled"}>
                        Convert
                    </button>
                </div>
            </div>
        `}function x(e){const a={"Pre-boarding":{bg:"bg-yellow-400",text:"text-yellow-800"},"Pending Docs":{bg:"bg-orange-400",text:"text-orange-800"},"IT Pending":{bg:"bg-blue-400",text:"text-blue-800"},"Joining Soon":{bg:"bg-indigo-500",text:"text-indigo-800"},Completed:{bg:"bg-green-400",text:"text-green-800"},Overdue:{bg:"bg-red-400",text:"text-red-800"}}[e]||{bg:"bg-gray-400",text:"text-gray-800"};return`<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${a.bg} ${a.text}">${s(e)}</span>`}function P(){document.querySelectorAll(".row-checkbox").forEach(e=>{e.addEventListener("change",function(t){const a=parseInt(this.dataset.id);t.target.checked?n.selectedIds.add(a):n.selectedIds.delete(a),N(),y()})}),document.querySelectorAll(".action-btn").forEach(e=>{e.addEventListener("click",function(t){t.stopPropagation();const a=this.dataset.action,o=parseInt(this.dataset.id);w(a,o)})}),document.querySelectorAll("tr[data-id]").forEach(e=>{e.addEventListener("click",function(t){if(!t.target.closest("button")&&!t.target.closest("input")){const a=parseInt(this.dataset.id);w("view",a)}})})}async function w(e,t){switch(e){case"view":await j(t);break;case"edit":c("Edit onboarding modal will open here","info");break;case"convert":await C(t);break}}async function j(e){try{const t=await fetch(`${u}/${e}`);if(!t.ok)throw new Error("Failed to load onboarding details");const a=await t.json(),o=document.getElementById("slide-over"),r=document.getElementById("slide-over-title");r&&(r.textContent=`Onboarding: ${a.fullName}`),o?.classList.remove("hidden"),E("overview",a)}catch(t){console.error("Error loading onboarding details:",t),p("Failed to load onboarding details")}}function b(){document.getElementById("slide-over")?.classList.add("hidden")}function E(e,t=null){document.querySelectorAll(".tab-btn").forEach(r=>{r.classList.remove("active","border-purple-500","text-purple-600"),r.classList.add("border-transparent","text-gray-500")});const a=document.querySelector(`[data-tab="${e}"]`);a&&(a.classList.add("active","border-purple-500","text-purple-600"),a.classList.remove("border-transparent","text-gray-500"));const o=document.getElementById("tab-content");o&&(o.innerHTML=`<p class="text-gray-500">${e.charAt(0).toUpperCase()+e.slice(1)} tab content will be displayed here.</p>`)}async function C(e){try{const t=await fetch(`${u}/${e}/convert`,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]')?.content||""}});if(!t.ok){const o=await t.json();throw new Error(o.message||"Failed to convert onboarding")}const a=await t.json();c("Onboarding converted to employee. Employee account created.","success"),l()}catch(t){console.error("Error converting onboarding:",t),p(t.message||"Failed to convert onboarding")}}async function O(){if(n.selectedIds.size!==0)try{if(!(await fetch(`${u}/bulk/remind`,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]')?.content||""},body:JSON.stringify({ids:Array.from(n.selectedIds)})})).ok)throw new Error("Failed to send reminders");c("Reminder sent to selected candidates.","success"),n.selectedIds.clear(),y(),f()}catch(e){console.error("Error sending reminders:",e),p("Failed to send reminders")}}async function T(){n.selectedIds.size!==0&&c("Bulk mark complete will be implemented","info")}function M(){if(n.selectedIds.size===0)return;const e=n.filteredOnboardings.filter(t=>n.selectedIds.has(t.id));A(e)}function D(){const e=new URLSearchParams({...Object.fromEntries(Object.entries(n.filters).filter(([t,a])=>a!==""))});window.location.href=`${u}/export/csv?${e}`}function A(e){const t=["candidate_name","email","role","department","manager","joining_date","progress_percent","status","last_updated"],a=e.map(d=>[d.fullName,d.email,d.designation,d.department,d.manager,d.joiningDate,d.progressPercent,d.status,d.lastUpdated]),o=[t.join(","),...a.map(d=>d.map(U=>`"${U}"`).join(","))].join(`
`),r=new Blob([o],{type:"text/csv"}),i=window.URL.createObjectURL(r),m=document.createElement("a");m.href=i,m.download=`onboardings-selected-${new Date().toISOString().split("T")[0]}.csv`,m.click(),window.URL.revokeObjectURL(i)}function z(){console.log("openImportModal called");const e=document.getElementById("import-modal");e?e.classList.remove("hidden"):(console.log("Creating import modal"),R())}function R(){console.log("createImportModal called");const e=document.createElement("div");e.id="import-modal",e.className="fixed inset-0 z-50 overflow-hidden",e.setAttribute("aria-labelledby","import-modal-title"),e.setAttribute("role","dialog"),e.setAttribute("aria-modal","true"),e.innerHTML=`
            <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="import-modal-backdrop"></div>
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 id="import-modal-title" class="text-base font-semibold leading-6 text-gray-900 mb-4">Import Candidates</h3>
                                    <form id="import-form" enctype="multipart/form-data" class="space-y-4">
                                        <div>
                                            <label for="import-file" class="block text-sm font-medium text-gray-700 mb-2">Select CSV/Excel File</label>
                                            <input type="file" 
                                                   id="import-file" 
                                                   name="file" 
                                                   accept=".csv,.xlsx,.xls"
                                                   required
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                            <p class="mt-1 text-xs text-gray-500">CSV or Excel file (max 10MB)</p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <a href="${u}/import/template" 
                                               class="text-sm text-purple-600 hover:text-purple-800">
                                                Download Template
                                            </a>
                                            <div class="flex gap-2">
                                                <button type="button" 
                                                        id="cancel-import"
                                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                    Cancel
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700">
                                                    Import
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,document.body.appendChild(e),document.getElementById("import-modal-backdrop")?.addEventListener("click",g),document.getElementById("cancel-import")?.addEventListener("click",g),document.getElementById("import-form")?.addEventListener("submit",F);const t=a=>{a.key==="Escape"&&(g(),document.removeEventListener("keydown",t))};document.addEventListener("keydown",t)}function g(){const e=document.getElementById("import-modal");e&&(e.classList.add("hidden"),setTimeout(()=>e.remove(),300))}async function F(e){e.preventDefault();const t=e.target,a=new FormData(t),o=document.getElementById("import-file");if(!o.files.length){p("Please select a file to import");return}a.append("file",o.files[0]);try{const r=await fetch(`${u}/import/candidates`,{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]')?.content||""},body:a});if(r.redirected){window.location.href=r.url;return}const i=await r.json();i.success?(c(i.message||"Candidates imported successfully","success"),g(),setTimeout(()=>l(),1e3)):p(i.message||"Import failed")}catch(r){console.error("Error importing candidates:",r),p("Failed to import candidates. Please try again.")}}function H(){const e=document.getElementById("pagination-container");if(!e)return;const t=Math.ceil(n.total/n.pageSize);if(t<=1){e.innerHTML="";return}let a='<div class="flex items-center justify-between"><div class="flex items-center space-x-2">';a+=`<button type="button" 
                         class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 ${n.currentPage===1?"opacity-50 cursor-not-allowed":""}"
                         data-page="${n.currentPage-1}"
                         ${n.currentPage===1?"disabled":""}>
                    Previous
                 </button>`;for(let o=1;o<=t;o++)o===1||o===t||o>=n.currentPage-1&&o<=n.currentPage+1?a+=`<button type="button" 
                                 class="pagination-btn px-3 py-2 text-sm font-medium ${o===n.currentPage?"bg-purple-600 text-white":"text-gray-700 bg-white border border-gray-300"} rounded-md hover:bg-gray-50"
                                 data-page="${o}">
                            ${o}
                         </button>`:(o===n.currentPage-2||o===n.currentPage+2)&&(a+='<span class="px-3 py-2 text-sm text-gray-700">...</span>');a+=`<button type="button" 
                         class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 ${n.currentPage===t?"opacity-50 cursor-not-allowed":""}"
                         data-page="${n.currentPage+1}"
                         ${n.currentPage===t?"disabled":""}>
                    Next
                 </button>`,a+="</div>",a+=`<div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-700">Show:</label>
                    <select id="page-size-select" class="px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <option value="10" ${n.pageSize===10?"selected":""}>10</option>
                        <option value="25" ${n.pageSize===25?"selected":""}>25</option>
                        <option value="50" ${n.pageSize===50?"selected":""}>50</option>
                    </select>
                 </div></div>`,e.innerHTML=a,document.querySelectorAll(".pagination-btn").forEach(o=>{o.addEventListener("click",function(){const r=parseInt(this.dataset.page);r&&r!==n.currentPage&&(n.currentPage=r,l())})}),document.getElementById("page-size-select")?.addEventListener("change",function(o){n.pageSize=parseInt(o.target.value),n.currentPage=1,l()})}function N(){const e=document.getElementById("select-all-checkbox");if(e){const t=n.filteredOnboardings.length>0&&n.filteredOnboardings.every(a=>n.selectedIds.has(a.id));e.checked=t,e.indeterminate=!t&&n.selectedIds.size>0}}function y(){const e=document.getElementById("bulk-actions-toolbar"),t=document.getElementById("bulk-selection-count");n.selectedIds.size>0?(e?.classList.remove("hidden"),t&&(t.textContent=`${n.selectedIds.size} selected`)):e?.classList.add("hidden")}function q(){document.getElementById("loading-skeleton")?.classList.remove("hidden"),document.getElementById("onboardings-tbody").innerHTML="",document.getElementById("mobile-cards").innerHTML=""}function k(){document.getElementById("loading-skeleton")?.classList.add("hidden")}function c(e,t="info"){const a=document.getElementById("toast-container");if(!a)return;const o={success:"bg-green-500",error:"bg-red-500",info:"bg-blue-500"},r=document.createElement("div");r.className=`${o[t]||o.info} text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between min-w-[300px]`,r.innerHTML=`
            <span>${s(e)}</span>
            <button type="button" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `,a.appendChild(r),r.querySelector("button").addEventListener("click",()=>{r.remove()}),setTimeout(()=>{r.remove()},5e3)}function p(e){c(e,"error")}function $(e){return e.split(" ").map(t=>t[0]).join("").toUpperCase().slice(0,2)}window.loadOnboardings=l,window.showToast=c,window.showError=p;function I(e){const t=new Date(e);return`${["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][t.getMonth()]} ${String(t.getDate()).padStart(2,"0")}, ${t.getFullYear()}`}function s(e){const t=document.createElement("div");return t.textContent=e,t.innerHTML}})();
