// import axios from 'axios';
let state = {
    summary: [],
    asOfDate: ''
};
let getters = {
    // globalSummary: (state) => state.summary,
    // globalDate: (state) => state.asOfDate
};
const actions = {
    // async fetchGlobal({commit}){
    //     const response = await axios.get("https://api.covid19api.com/summary");
    //     commit('setGlobal', response.data.Global)
    //     commit('setAsOfDate', moment(response.data.Date).format("MMMM Do YYYY"))
    // }
};
const mutations = {
    // setGlobal: (state, summary) => (state.summary = summary),
    // setAsOfDate: (state, asOfDate) => (state.asOfDate = asOfDate)
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}