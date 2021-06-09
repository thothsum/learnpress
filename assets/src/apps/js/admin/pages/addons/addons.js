import handleAjax from '../../../utils/handle-ajax-api';

const $ = jQuery;

const addons = () => {
	const elFreeAddons = document.querySelector( '#lp-addons-free' );

	const getFreeAddons = () => {
		if ( elFreeAddons ) {
			const elUl = elFreeAddons.querySelector( '.wrapper-list-lp-addons' );

			const callBack = {
				success: ( res ) => {
					const { status, data: { plugin_wp }, message } = res;

					if ( 'success' === status ) {
						if ( plugin_wp.length > 0 ) {
							const elStructHtmlItem = elUl.querySelector( '.struct-html-item-addons' );
							const itemLi = elStructHtmlItem.querySelector( 'li' );

							elUl.style.display = 'block';

							plugin_wp.forEach( ( item ) => {
								const itemLiClone = itemLi.cloneNode( true );

								const imgIcon = itemLiClone.querySelector( '.plugin-icon' ).querySelector( 'img' );
								const itemTitle = itemLiClone.querySelector( '.item-title' );
								const itemDes = itemLiClone.querySelector( '.desc' );
								const pDes = itemDes.querySelector( 'p:first-child' );
								const author = itemDes.querySelector( '.authors' );
								const actionLinks = itemLiClone.querySelector( '.action-links' ).querySelector( '.plugin-action-buttons' );
								actionLinks.innerHTML = '';

								itemLiClone.setAttribute( 'id', 'learn-press-plugin-' + item.slug );
								imgIcon.setAttribute( 'src', item.icons[ '1x' ] );
								itemTitle.innerHTML = item.name;
								pDes.innerHTML = item.short_description;
								author.innerHTML = item.author;

								if ( undefined != item.link_actions ) {
									item.link_actions.forEach( ( link ) => {
										const liBtn = document.createElement( 'li' );
										liBtn.innerHTML = link;
										actionLinks.append( liBtn );
									} );
								}

								elUl.append( itemLiClone );
							} );

							elStructHtmlItem.remove();
						} else {

						}
					} else {
						console.log( message );
					}
				},
				error: ( err ) => {

				},
				completed: () => {

				},
			};
			handleAjax( '/lp/v1/admin/themes-addons/get-addons', '', callBack, 'GET' );

			// Events
			pluginActions();
		}
	};

	const pluginActions = function pluginActions() {
		elFreeAddons.addEventListener( 'click', ( e ) => {
			e.preventDefault();

			if ( ! e.target.classList.contains( 'lp-btn-addon-action' ) ) {
				return;
			}

			const __el = e.target;

			__el.classList.add( 'updating-message' );

			const callBack = {
				success: ( res ) => {
					const { status, message, data: { type } } = res;

					if ( 'success' === status ) {
						switch ( type ) {
						case 'install_free':
							__el.classList.add( 'activate-now', 'button-primary' );
							__el.classList.remove( 'install-now' );
							__el.textContent = 'Active Now';
							break;
						default:
							break;
						}
					}
				},
				error: ( err ) => {

				},
				completed: () => {
					__el.classList.remove( 'updating-message' );
				},
			};

			if ( __el.classList.contains( 'install-now' ) ) {
				const href = __el.getAttribute( 'href' );

				const data = { plugin_url: href };

				handleAjax( '/lp/v1/admin/themes-addons/install-addon-free', data, callBack );
			} else if ( __el.classList.contains( 'activate-now' ) ) {
				handleAjax( '/lp/v1/admin/themes-addons/activate-addon', {}, callBack );
			} else if ( __el.classList.contains( 'update-now' ) ) {
				handleAjax( '/lp/v1/admin/themes-addons/update-addon-free', {}, callBack );
			} else if ( __el.classList.contains( 'disable-now' ) ) {
				handleAjax( '/lp/v1/admin/themes-addons/disable-addon', {}, callBack );
			}
		} );
	};

	getFreeAddons();
};

export default addons;
