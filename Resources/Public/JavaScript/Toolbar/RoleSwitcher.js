/**
 * Module: @cron-eu/begroups-roles/toolbar/role-switcher
 */
import AjaxRequest from '@typo3/core/ajax/ajax-request.js';
import RegularEvent from '@typo3/core/event/regular-event.js';

class RoleSwitcher {
  constructor() {
    this.initializeEvents();
  }

  initializeEvents() {
    const container = document.querySelector('#ichhabrecht-begroupsroles-backend-toolbaritems-roleswitcher');

    new RegularEvent('click', (event, target) => {
      event.preventDefault();
      this.switchRole(target.dataset.role);
    }).delegateTo(container, '.dropdown-item');

    // Set up filter input
    new RegularEvent('input', (event, target) => {
      this.filterRoles(target.value);
    }).delegateTo(container, '#role-filter-input');

    // Focus filter input when dropdown opens
    new RegularEvent('show.bs.dropdown', () => {
      setTimeout(() => {
        const filterInput = document.querySelector('#role-filter-input');
        if (filterInput) {
          filterInput.focus();
        }
      }, 100);
    }).bindTo(container);
  }

  filterRoles(filterText) {
    const roleList = document.querySelector('#role-list');
    if (!roleList) {
      return;
    }

    const listItems = roleList.querySelectorAll('li');
    const searchTerm = filterText.toLowerCase().trim();

    listItems.forEach((item) => {
      if (searchTerm === '') {
        // No filter text, show all items
        item.style.display = '';
      } else {
        // Get the text content of the link
        const link = item.querySelector('a');
        const text = link ? link.textContent.toLowerCase() : '';

        if (text.includes(searchTerm)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      }
    });
  }

  switchRole(role) {
    (new AjaxRequest(TYPO3.settings.ajaxUrls['role_switch']))
      .post({ role: role })
      .then(async (response) => {
        const data = await response.resolve();
        // Redirect to main route
        document.location.assign(data.redirectUrl);
      })
      .catch((error) => {
        console.error('RoleSwitcher: Error occurred:', error);
      });
  }
}

export default new RoleSwitcher();
