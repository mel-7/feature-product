import BuilderDashboard from "../components/builder/BuilderDashboard";
import BuilderProducts from "../components/builder/BuilderProducts";
// import BuilderNewProduct from "../components/builder/BuilderNewProduct";
import BuilderEditProduct from "../components/builder/BuilderEditProduct";
import UploadVideo from "../components/builder/UploadVideo";

// Settings
import Account from "../components/settings/Account";
import Watermarks from "../components/settings/watermarks";
import EditWatermark from "../components/settings/watermark/EditWatermark";
import Teams from "../components/settings/Teams";
import Organization from "../components/settings/Organization";
import Companies from "../components/settings/Companies";

import RestrictedPage from "../components/RestrictedPage";

let authUser;
let authMeta = document.querySelector("meta[name='auth_user']").getAttribute("content");
if(authMeta.length){
    authUser = JSON.parse(authMeta);
}

export const routes = [
    {
        path: "/",
        component: BuilderDashboard
    },
    {
        path: "/restricted",
        component: RestrictedPage
    },
    {
        path: "/dashboard",
        component: BuilderDashboard
    },
    {
        path: "/builder",
        component: BuilderProducts
    },
    {
        path: "/builder/products",
        component: BuilderProducts
    },
    // {
    //     path: "/builder/product/new",
    //     component: BuilderNewProduct
    // },
    {
        path: "/builder/product/upload-video",
        component: UploadVideo
    },
    {
        path: "/builder/product/edit/:id",
        name: "BuilderEditProduct",
        component: BuilderEditProduct,
        props: true
    },
    // {
    //     path: '/admin/post/edit/:id',
    //     name: 'EditPost',
    //     component: EditPost,
    //     props: true
    // },

    // { // Org Settings
    //     path: "/settings/organization",
    //     name: 'OrgSettings',
    //     component: OrgSettings,
    //     props: true
    // },

    /**
     * Settings
     */
    {
        path: "/settings/companies",
        name: "Companies",
        component: Companies,
        props: true,
        beforeEnter: (to, from, next) => {
            if (authUser.role == 1) {
                next();
            }
        }
    },
    {
        path: "/settings/companies/page/:page",
        name: "PagedCompanies",
        component: Companies,
        props: true,
        beforeEnter: (to, from, next) => {
            if (authUser.role == 1) {
                next();
            }else {
                next({
                    name: "/restricted"
                });
            }
        }
    },
    {
        path: "/settings/account",
        name: "Account",
        component: Account,
        props: true
    },
    {
        path: "/settings/watermarks",
        name: "Watermarks",
        component: Watermarks,
        props: true
    },
    {
        path: "/settings/watermarks/page/:page",
        name: "PagedWatermarks",
        component: Watermarks,
        props: true
    },
    {
        path: "/settings/watermark/edit/:id",
        name: "Watermark",
        component: EditWatermark,
        props: true
    },
    {
        path: "/settings/organization",
        name: "Organization",
        component: Organization,
        props: true,
        beforeEnter: (to, from, next) => {
            if (authUser.role != 5) {
                next();
            } else {
                next({
                    name: "/dashboard"
                });
            }
        }
    },
    {
        path: "/settings/teams",
        name: "Teams",
        component: Teams,
        props: true
    }
];
