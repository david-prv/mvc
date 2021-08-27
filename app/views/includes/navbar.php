<a id="nani-logo-fixed" href="<?php echo URL_ROOT; ?>/users/index">
    <svg viewBox="0 0 1000 1000" version="1.1"
         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <use xlink:href="#nani-logo-full-duotone" />
    </svg>
    <p class="name">NANI</p>
</a>

<header id="main-header">
    <nav class="main-header-nav">
        <ul><li class="account-avatar-container">
            <a class="account-link" href="<?php echo URL_ROOT; ?>/users/account" title="Your account">
                <svg class="logged-out-account" viewBox="0 0 1000 1000" version="1.1"
                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <use xlink:href="#nani-logo-outline" class="ffdeg"></use>
                </svg>
            </a>
        </li><?php if (isLoggedIn()) { ?><li>
            <a class="logout-button" href="<?php echo URL_ROOT; ?>/users/logout" title="Log out">
                <svg viewBox="0 0 1000 1000" version="1.1"
                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <use xlink:href="#header-icon-logout"></use>
                </svg>
            </a>
        </li><?php } ?></ul>
    </nav>
</header>

<svg version="1.1" viewBox="0 0 1000 1000" style="display:none;visibility:hidden;"
     xmlns="http://www.w3.org/2000/svg">
    <defs>
        <path id="nani-logo-outline"
              d="M 228.00,171.08
                   C 228.00,171.08 282.00,171.08 282.00,171.08
                     282.00,171.08 384.00,171.08 384.00,171.08
                     384.00,171.08 696.00,171.08 696.00,171.08
                     696.00,171.08 741.00,171.08 741.00,171.08
                     771.59,171.00 799.34,168.25 819.23,197.00
                     821.96,200.95 825.13,207.43 826.64,212.00
                     828.77,218.45 829.99,229.17 830.00,236.00
                     830.00,236.00 830.00,693.00 830.00,693.00
                     830.00,693.00 830.00,737.00 830.00,737.00
                     830.00,768.57 833.81,798.61 804.00,819.23
                     799.77,822.15 792.93,825.46 788.00,826.96
                     781.89,828.83 771.44,829.99 765.00,830.00
                     765.00,830.00 308.00,830.00 308.00,830.00
                     308.00,830.00 264.00,830.00 264.00,830.00
                     232.43,830.00 202.39,833.81 181.77,804.00
                     178.85,799.77 175.54,792.93 174.04,788.00
                     172.17,781.89 171.01,771.44 171.00,765.00
                     171.00,765.00 171.00,315.00 171.00,315.00
                     171.00,315.00 171.00,258.00 171.00,258.00
                     171.00,231.68 168.83,205.59 191.01,186.44
                     195.77,182.33 199.38,179.99 205.00,177.26
                     214.41,172.68 218.19,172.85 228.00,171.08 Z
                   M 255.06,349.60
                   C 248.95,353.35 251.00,362.69 251.00,369.00
                     251.00,369.00 251.00,422.00 251.00,422.00
                     251.00,422.00 251.00,600.00 251.00,600.00
                     251.00,600.00 251.00,644.00 251.00,644.00
                     251.12,650.10 253.09,653.81 260.00,652.85
                     266.35,651.97 265.99,647.03 266.00,642.00
                     266.00,642.00 266.00,417.00 266.00,417.00
                     266.00,417.00 266.00,359.00 266.00,359.00
                     265.94,350.87 263.65,347.76 255.06,349.60 Z
                   M 368.00,349.08
                   C 356.48,352.09 349.15,361.20 349.00,373.00
                     349.00,373.00 349.00,474.00 349.00,474.00
                     349.00,474.00 349.00,629.00 349.00,629.00
                     349.19,644.47 360.24,652.98 375.00,653.00
                     375.00,653.00 589.00,653.00 589.00,653.00
                     589.00,653.00 619.00,653.00 619.00,653.00
                     623.54,653.00 631.95,653.38 636.00,652.21
                     643.11,650.16 650.16,643.11 652.21,636.00
                     653.10,632.93 653.00,630.15 653.00,627.00
                     653.00,627.00 653.00,413.00 653.00,413.00
                     653.00,413.00 653.00,383.00 653.00,383.00
                     653.00,378.46 653.38,370.05 652.21,366.00
                     650.13,358.79 643.21,351.87 636.00,349.79
                     632.93,348.90 630.15,349.00 627.00,349.08
                     627.00,349.08 456.00,349.08 456.00,349.08
                     456.00,349.08 399.00,349.08 399.00,349.08
                     399.00,349.08 368.00,349.08 368.00,349.08 Z
                   M 234.00,689.16
                   C 230.15,690.19 226.46,691.42 223.00,693.35
                     202.27,704.88 206.00,726.09 206.00,746.00
                     206.00,760.16 204.26,771.26 214.47,782.91
                     218.44,787.45 225.17,791.50 231.00,793.07
                     235.88,794.39 242.82,794.00 248.00,794.00
                     248.00,794.00 276.00,794.00 276.00,794.00
                     280.59,793.99 283.55,794.02 288.00,792.45
                     290.95,791.41 294.44,789.57 297.00,787.78
                     314.36,775.68 311.00,756.33 311.00,738.00
                     311.00,724.87 312.77,714.49 304.78,703.00
                     293.16,686.31 276.55,689.00 259.00,689.16
                     259.00,689.16 234.00,689.16 234.00,689.16 Z"></path>

        <path id="header-icon-logout" class="header-icon"
              d="m406.5 424.58c-93.927 0-93.927 151.16 0 151.16h162.03v172.73l265.53-226.83c13.575-12.115 13.575-30.598 0-43.398l-265.53-226.72v173.19zm63.429 252.76v71.595h-64.252c-333.23 0-333.23-497.05 0-497.05h64.252v71.912h-64.252c-236.73 0-236.73 353.54 0 353.54z" />
        <g id="nani-logo-full-duotone">
            <path class="nani-logo-back"
                  d="m171.25 343.72v313.78h198.77v-5.8691c-11.316 0-20.504-9.391-20.504-20.947v-262.11c0-8.7772 11.394-19.854 20.492-19.854v-5zm94.373 0.11241v313.6c-14.217 0 0 0.0542-14.217 0.0542l0.1582-313.72c14.059 0 0 0.0641 14.059 0.0641z" />
            <path class="nani-logo-front"
                  d="m223.69 171.04c-27.189 0-52.438 29.764-52.438 52.734v124.95h200.82 259.51c11.075 0 20.727 11.26 20.727 19.854v262.11c0 12.107-10.985 20.945-20.725 20.945h-260.57-199.77l0.00195 124.36c0 25.444 27.075 52.973 52.436 52.973h553.42c23.153 0 52.736-25.905 52.736-52.973v-552.22c0-24.259-25.094-52.734-52.736-52.734h-553.42zm12.084 518.37h45.496c14.859 0 29.832 18.052 29.832 28.984v45.713c0 12.735-14.973 29.832-29.832 29.832h-45.496c-13.145 0-29.459-17.097-29.459-29.832v-45.713c0-10.933 16.314-28.984 29.459-28.984z" />
        </g>
    </defs>
</svg>