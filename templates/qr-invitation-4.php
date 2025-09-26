<?php
    $page_url = preg_replace('#^https?://#', 'www.', $page_url);
?>

<div class="InvitationContainer">

    <div id="inviteCard" class="invitationQrcode container invTemplate-4">

        <div class="col col-1" style="background-image:url('<?php echo esc_url( $bg_url ); ?>');" ></div>

        <div class="col col-2">
            <div class="topHeading">
                <h5>THOMAS</h5>
                <p>and</p>
                <h5>CHRISTINE</h5>
            </div>
            <div class="qrCodeSection">
                <img src="<?php echo esc_attr( $dataUri ); ?>" alt="QR Code" />
            </div>
            <div class="bottomSection">
                <p>JOYFULL INVITE YOU TO THE CELEBRATION OF THEIR MARRIEAGE</p>
                <p class="weddingDate">12.15.2026</p>
                <p class="invitationUrl">WWW.BESTWISHES.COM/SERENITY</p>
            </div>
        </div>

    </div>

    <div class="overlayDownloadInvitation">

        <button id="downloadInviteBtn" class="btnInvitation">Download Your Invitation <img src="https://localhost/bestwishes/wp-content/uploads/2025/09/file-1.png"/></button>

    </div>



</div>



<style>

.InvitationContainer {
    position: relative;
    display: flex;
    justify-content: center;
}


.invTemplate-4 {
    display: flex;
    flex-direction: row;
    height: 600px;
    width: 100%;
    max-width: 600px;
    border: 1px solid #d3d3d3;
}

.invTemplate-4  .col {
    height: 100%;
    flex: 1 1 0%;
}

.invTemplate-4 .col-1 {
    background-size: cover;
    background-position: center;
}


.invTemplate-4 .col-2 {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    background: #ededed;
}

.topHeading {
    text-align: center;
    margin-bottom: 10px;
}

.qrCodeSection, .bottomSection {
    text-align: center;
}

.qrCodeSection img {
    width: 150px;
}

.qrCodeSection {
    margin: 20px 0px;
}

.topHeading h5 {
    font-size: 35px;
    letter-spacing: 0.7px;
    margin:0px;
}

.topHeading p {
    margin:10px;
}

.bottomSection p:nth-child(1) {
    text-transform: uppercase;
    font-style: italic;
    font-size: 14px;
}

p.weddingDate {
    font-size: 25px;
    font-weight: 600;
}



.overlayDownloadInvitation {
  display: none;
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */
  position: absolute; /* To make the overlay cover the whole viewport */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
}

.btnInvitation {
    padding: 15px 20px 15px 30px;
    font-size: 18px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btnInvitation img {
  width: 30px;
  height: 30px;
}


.InvitationContainer:hover .overlayDownloadInvitation {
    display:flex;
}

</style>