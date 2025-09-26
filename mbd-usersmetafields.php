<?php 


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// ===== Add Custom Fields to User Profile =====
function md_add_custom_user_fields( $user ) { ?>
    <h3>Members Dashboard - Invitation Settings</h3>
    <table class="form-table">
        <tr>
            <th><label for="invitation_page_id">Invitation Page ID</label></th>
            <td>
                <input type="text" name="invitation_page_id" id="invitation_page_id"
                    value="<?php echo esc_attr( get_user_meta( $user->ID, 'invitation_page_id', true ) ); ?>"
                    class="regular-text" />
                <p class="description">Enter the Page ID for the Invitation Page.</p>
            </td>
        </tr>
        <tr>
            <th><label for="Groom Name">Grooms Full Name</label></th>
            <td>
                <input type="text" name="groom_full_name" id="groom_full_name"
                    value="<?php echo esc_attr( get_user_meta( $user->ID, 'groom_full_name', true ) ); ?>"
                    class="regular-text" />
                <p class="description">Enter Share Page Moment ID</p>
            </td>
        </tr>
        <tr>
            <th><label for="Bride Name">Brides Full Name</label></th>
            <td>
                <input type="text" name="bride_full_name" id="bride_full_name"
                    value="<?php echo esc_attr( get_user_meta( $user->ID, 'bride_full_name', true ) ); ?>"
                    class="regular-text" />
                <p class="description">Enter Share Page Moment ID</p>
            </td>
        </tr>
        <tr>
            <th><label for="wedding_date">Wedding Date</label></th>
            <td>
                <input type="date" 
                    name="wedding_date" 
                    id="wedding_date"
                    value="<?php echo esc_attr( get_user_meta( $user->ID, 'wedding_date', true ) ); ?>" 
                    class="regular-text" />
                <p class="description">Select the wedding date</p>
            </td>
        </tr>      
        <tr>
            <th><label for="rsvp_form_id">RSVP Form ID</label></th>
            <td>
                <input type="text" name="rsvp_form_id" id="rsvp_form_id"
                    value="<?php echo esc_attr( get_user_meta( $user->ID, 'rsvp_form_id', true ) ); ?>"
                    class="regular-text" />
                <p class="description">Enter the Form ID for RSVP.</p>
            </td>
        </tr>
        <tr>
            <th><label for="profile_picture">Profile Picture</label></th>
            <td>
                <?php $profile_picture = get_user_meta( $user->ID, 'profile_picture', true ); ?>
                <input type="text" name="profile_picture" id="profile_picture"
                    value="<?php echo esc_attr( $profile_picture ); ?>" class="regular-text" />
                <input type="button" class="button button-secondary" value="Upload Image" id="upload_profile_picture_button" />
                <p class="description">Upload or paste the URL of the profile picture.</p>
                <?php if ( $profile_picture ) : ?>
                    <br><img src="<?php echo esc_url( $profile_picture ); ?>" style="max-width:100px; margin-top:10px;" />
                <?php endif; ?>
            </td>
        </tr>
    </table>
<?php }
add_action( 'show_user_profile', 'md_add_custom_user_fields' );
add_action( 'edit_user_profile', 'md_add_custom_user_fields' );

// ===== Save Custom Fields =====
function md_save_custom_user_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) return false;

    if ( isset( $_POST['invitation_page_id'] ) ) {
        update_user_meta( $user_id, 'invitation_page_id', sanitize_text_field( $_POST['invitation_page_id'] ) );
    }
    if ( isset( $_POST['moment_capture_page_id'] ) ) {
        update_user_meta( $user_id, 'moment_capture_page_id', sanitize_text_field( $_POST['moment_capture_page_id'] ) );
    }
    if ( isset( $_POST['groom_full_name'] ) ) {
        update_user_meta( $user_id, 'groom_full_name', sanitize_text_field( $_POST['groom_full_name'] ) );
    }
    if ( isset( $_POST['bride_full_name'] ) ) {
        update_user_meta( $user_id, 'bride_full_name', sanitize_text_field( $_POST['bride_full_name'] ) );
    }
    if ( isset( $_POST['wedding_date'] ) ) {
        update_user_meta( $user_id, 'wedding_date', sanitize_text_field( $_POST['wedding_date'] ) );
    }    
    if ( isset( $_POST['rsvp_form_id'] ) ) {
        update_user_meta( $user_id, 'rsvp_form_id', sanitize_text_field( $_POST['rsvp_form_id'] ) );
    }
    if ( isset( $_POST['profile_picture'] ) ) {
        update_user_meta( $user_id, 'profile_picture', esc_url_raw( $_POST['profile_picture'] ) );
    }
}
add_action( 'personal_options_update', 'md_save_custom_user_fields' );
add_action( 'edit_user_profile_update', 'md_save_custom_user_fields' );








