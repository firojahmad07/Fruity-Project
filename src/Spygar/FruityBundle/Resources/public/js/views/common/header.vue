<script>
export default {
    name: "header"
};
</script>
<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Fruity</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02"
                    aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarColor02">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link to="/" tab="a" class="nav-link">Fruits</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/favorite/fruites" tab="a" class="nav-link">favorites</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>