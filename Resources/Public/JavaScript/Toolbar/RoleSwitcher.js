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
