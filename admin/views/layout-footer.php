        </main>
        </div>

        <?php if (isset($extra_js)): ?>
            <?php foreach ($extra_js as $js): ?>
                <script src="<?php echo htmlspecialchars($js); ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>

        <script>
            // Controle do menu mobile
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-item');

            function setMenuState(isOpen) {
                if (!menuToggle || !sidebar || !sidebarOverlay) {
                    return;
                }

                sidebar.classList.toggle('active', isOpen);
                sidebarOverlay.classList.toggle('active', isOpen);
                document.body.classList.toggle('menu-open', isOpen);

                menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                sidebarOverlay.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            }

            if (menuToggle && sidebar && sidebarOverlay) {
                menuToggle.addEventListener('click', () => {
                    const shouldOpen = !sidebar.classList.contains('active');
                    setMenuState(shouldOpen);
                });

                sidebarOverlay.addEventListener('click', () => {
                    setMenuState(false);
                });

                sidebarLinks.forEach((link) => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth <= 768) {
                            setMenuState(false);
                        }
                    });
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setMenuState(false);
                    }
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth > 768) {
                        setMenuState(false);
                    }
                });

                setMenuState(false);
            }
        </script>
        </body>

        </html>