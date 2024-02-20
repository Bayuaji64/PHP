
import './bootstrap';


import Search from './live-search';
import Chat from './chat';
import Profile from './profile';

// alert("This is a test 123")
// if (document.querySelector(".header-search-icon")) {
//     new Search()
// }
// if (document.querySelector(".header-search-icon")) {
//     new Chat()
// }

// if (document.querySelector(".header-search-icon")) {
//     new Profile()
// }


if (document.querySelector(".header-search-icon")) {
    new Search();
    new Chat();
    new Profile();
}


