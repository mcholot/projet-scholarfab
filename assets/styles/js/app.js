import '../css/app.scss';

// MENU

import { Dropdown } from 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    enableDropdown();
});

const enableDropdown = () => {
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new Dropdown(dropdownToggleEl)
    });
}