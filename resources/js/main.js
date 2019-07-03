$( document ).ready(function() {
    $('.add-guest').on('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        let playerID = $(e.target).data('player');
		let guestField = $('.guest-field[data-player=' + playerID + ']');
		let playerField = $('.friend-selector[data-player=' + playerID + ']');

        guestField.show();
		guestField.attr('required', 'required');
		playerField.removeAttr('required');
		playerField.val('');
    });

    $('.friend-selector').change((e) => {
        let playerID = $(e.target).data('player');
        let guestField = $('.guest-field[data-player=' + playerID + ']');
        let playerField = $('.friend-selector[data-player=' + playerID + ']');

		playerField.attr('required', 'required');
		guestField.hide();
		guestField.removeAttr('required');
    });
});
