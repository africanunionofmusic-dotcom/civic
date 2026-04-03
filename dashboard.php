<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AUOM Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>🎧 AUOM</h2>

        <ul>
            <li class="active">🏠 Home</li>
            <li>🎵 My Music</li>
            <li>⬆ Upload</li>
            <li>💰 Earnings</li>
            <li>⚙ Settings</li>
        </ul>
    </div>

    <!-- Main -->
    <div class="main">

        <!-- Topbar -->
        <div class="topbar">
            <input type="text" placeholder="Search music...">

            <div class="profile">
                <span><?php echo $_SESSION['user']; ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Trending Music 🔥</h2>

            <div class="music-grid">

                <div class="music-card">
                    <img src="images/zaabu.jpg" alt="zaabu" class="cover">
                    <p>Zaabu (Album)</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                    <img src="images/pol.jpg" alt="pol" class="cover">
                    <p>Proof of Life (Album)</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                    <img src="images/mie.jpg" alt="mie" class="cover">
                    <p>Made in Entebbe (Album)</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                    <div class="cover"></div>
                    <p>Heavy Muchwezi (Album)</p>
                    <span>Coming Up</span>
                </div>


                <div class="music-card">
                    <img src="images/bs.jpg" alt="bs" class="cover">
                    <p>Proof of Life (Album)</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                    <img src="images/xv.jpg" alt="xv" class="cover">
                    <p>XV Ep</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                    <img src="images/sound.jpg" alt="sound" class="cover">
                    <p>Sound Vol 1</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                    <img src="images/fly.jpg" alt="fly" class="cover">
                    <p>How to Fly (single)</p>
                    <span>SoundlykBB</span>
                </div>


                <div class="music-card">
                     <img src="images/day.jpg" alt="day" class="cover">
                    <p>Thursday</p>
                    <span>SoundlykBB ft TaiDai & Kaboo</span>
                </div>

                <div class="music-card">
                    <img src="images/henny.jpg" alt="henny" class="cover">
                    <p>Hennesy and Ganja</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                     <img src="images/lyato.jpg" alt="lyato" class="cover">
                    <p>Lyato</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                     <img src="images/happier.jpg" alt="happier" class="cover">
                    <p>Happier Ep</p>
                    <span>SoundlykBB</span>
                </div>


                <div class="music-card">
                     <img src="images/bb2.jpg" alt="bb2" class="cover">
                    <p>SoundlykBB2 Ep</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                     <img src="images/deleted.jpg" alt="deleted" class="cover">
                    <p>Deleted Ep</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                     <img src="images/bb3.jpg" alt="bb3" class="cover">
                    <p>SoundlykBB3 Ep</p>
                    <span>SoundlykBB</span>
                </div>

                <div class="music-card">
                     <img src="images/casino.jpg" alt="casino" class="cover">
                    <p>Casino</p>
                    <span>SoundlykBB ft TaiDai & Sente</span>
                </div>
            

                <div class="music-card">
                     <img src="images/nolie.jpg" alt="nolie" class="cover">
                    <p>No lie</p>
                    <span>SoundlykBB ft Tungi</span>
                </div>

                <div class="music-card">
                    <img src="images/niqqa.jpg" alt="niqqa" class="cover">
                    <p>Niggas in Fashion</p>
                    <span>SoundlykBB ft Denim Kartel, TaiDai & Axon</span>
                </div>

                <div class="music-card">
                    <img src="images/depend.jpg" alt="depend" class="cover">
                    <p>It all Depends</p>
                    <span>I-BladeSoundlykBB</span>
                </div>

                <div class="music-card">
                     <img src="images/ola.jpg" alt="ola" class="cover">
                    <p>Ola Mummy</p>
                    <span>SoundlykBB</span>
                </div>


                <div class="music-card">
                     <img src="images/ndiisa.jpg" alt="ndiisa" class="cover">
                    <p>Ndiisa</p>
                    <span>SoundlykBB</span>
                </div>

            </div>
        </div>

    </div>

</div>

</body>
</html>