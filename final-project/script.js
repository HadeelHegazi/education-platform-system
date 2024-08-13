const allSideMenue = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenue.forEach(item=> {
    const li=item.parentElement;

    item.addEventListener('click', function(){
        allSideMenue.forEach(i=> {
            i.parentElement.classList.remove('active')
        })
        li.classList.add('active');
    })
})



// TOGGLE SIDEBAR
const menuBar = document.querySelector('#profilecontent nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})


const searchButton = document.querySelector('#profilecontent nav form .form-input button');
const searchButtonIcon = document.querySelector('#profilecontent nav form .form-input button .bx');
const searchForm = document.querySelector('#profilecontent nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})

if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})


const switchMode = document.getElementById('profileswitch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})



// MAIN 

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar menu functionality
    const allSideMenuItems = document.querySelectorAll('#sidebar .side-menu.top li a');
    const allContentSections = document.querySelectorAll('#profilecontent main .main-content .content-section');

    allSideMenuItems.forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault() // Prevent default link behavior

            // Remove 'active' class from all sidebar items
            allSideMenuItems.forEach(menuItem => {
                menuItem.parentElement.classList.remove('active')
            })

            // Add 'active' class to the clicked sidebar item
            item.parentElement.classList.add('active')

            // Get the target section ID from the data-target attribute
            const targetSectionId = item.getAttribute('data-target')

            // Hide all content sections
            allContentSections.forEach(section => {
                section.classList.remove('active')
            })

            // Show the target content section
            const targetSection = document.getElementById(targetSectionId);
            if (targetSection) {
                targetSection.classList.add('active')
            }
        })
    })
})


// MASSEGE 
// const form = document.querySelector(".typing-area");
// // const incoming_id = form.querySelector(".receiver_id").value;
// const inputField = form.querySelector(".input-field").value;
// const sendMessageBtn = form.querySelector(".messagebtn");
// const messageChatBox = document.querySelector(".messagechatbox");


// form.onsubmit = (e) => {
//     e.preventDefault();
// }

// inputField.focus();

// inputField.onkeyup = ()=> {
//     if(inputField.value != ""){
//         sendMessageBtn.classList.add("active");
//     }else{
//         sendMessageBtn.classList.remove("active");
//     }
// }

// sendMessageBtn.onclick = () =>{
//     let = new XMLHttpRequest();
//     xhr.open("POST", "insert-chat.php",true);
//     xhr.onload = () => {
//         if(xhr.readyState === XMLHttpRequest.DONE){
//             if(xhr.status === 200){
//                 inputField.value = "";
//                 scrollToBottom();
//             }
//         }
//     }
//     let formData = new FormData(form);
//     xhr.send(formData);
// }


// const form = document.querySelector(".typing-area");
// const inputField = form.querySelector(".input-field");
// const sendMessageBtn = form.querySelector(".messagebtn");

// form.onsubmit = (e) => {
//     e.preventDefault();
// }

// inputField.focus(); // Corrected typo in focus()

// inputField.onkeyup = () => {
//     if (inputField.value.trim() !== "") { // Added .trim() to ensure spaces aren't counted
//         sendMessageBtn.classList.add("active");
//     } else {
//         sendMessageBtn.classList.remove("active");
//     }
// }

// sendMessageBtn.onclick = () => {
//     const xhr = new XMLHttpRequest(); // Corrected variable name to xhr
//     xhr.open("POST", "insert-chat.php", true);
//     xhr.onload = () => {
//         if (xhr.readyState === XMLHttpRequest.DONE) { // Corrected typo in readyState
//             if (xhr.status === 200) {
//                 inputField.value = "";
//                 scrollToBottom();
//                 // Optionally, you can handle the response here if needed
//             }
//         }
//     }
//     let formData = new FormData(form);
//     xhr.send(formData);
// }


// messageChatBox.onmouseenter = () => {
//     messageChatBox.classList.add("active");
// }
// messageChatBox.onmouseleave = () => {
//     messageChatBox.classList.remove("active");
// }
// function scrollToBottom() {
//     messageChatBox.scrollTop= messageChatBox.scrollHeight;
// }
// MASSEGE 