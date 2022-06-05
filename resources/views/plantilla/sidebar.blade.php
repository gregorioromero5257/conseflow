<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <template v-for="item in elementosMenu">
                <li class="nav-item nav-dropdown" v-if="item.page == null" >
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i v-bind:class="item.clase"></i>@{{ item.name }}</a>
                    <ul class="nav-dropdown-items" v-if="item.name">
                        <li class="nav-item" v-for="submenu in elSubMenu">
                            <router-link :to="submenu.page" class="nav-link" v-if="item.name == submenu.name">
                                <i v-bind:class="submenu.clase"></i> @{{ submenu.submenu }}
                            </router-link>
                        </li> 
                    </ul>
                </li>
                <li class="nav-item" v-if="item.page != null">
                    <router-link :to="item.page" class="nav-link">
                        <i v-bind:class="item.clase" ></i>@{{ item.name }}</router-link>
                </li>
            </template>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>