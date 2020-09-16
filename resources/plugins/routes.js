import BuilderDashboard from "../js/components/builder/BuilderDashboard";
import BuilderProducts from "../js/components/builder/BuilderProducts";
// import BuilderNewProduct from "../js/components/builder/BuilderNewProduct";
import BuilderEditProduct from "../js/components/builder/BuilderEditProduct";
import UploadVideo from "../js/components/builder/UploadVideo";

// Settings
import Account from "../js/components/settings/Account"
import Watermark from "../js/components/settings/Watermark"
import Teams from "../js/components/settings/Teams"
import Organization from "../js/components/settings/Organization"
import Companies from "../js/components/settings/Companies"

export const routes = [
    {
        path: "/",
        component: BuilderDashboard
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
        name: 'BuilderEditProduct',
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
        name: 'Companies',
        component: Companies,
        props: true
    },
    {
        path: "/settings/account",
        name: 'Account',
        component: Account,
        props: true
    },
    {
        path: "/settings/watermark",
        name: 'Watermark',
        component: Watermark,
        props: true
    },
    {
        path: "/settings/organization",
        name: 'Organization',
        component: Organization,
        props: true
    },
    {
        path: "/settings/teams",
        name: 'Teams',
        component: Teams,
        props: true
    }
];
