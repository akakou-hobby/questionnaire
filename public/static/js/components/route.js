const router = new VueRouter({
  mode: "hash",
  // base:'/app/public/',
  routes: [
    {
      path: "/",
      component: CreateFormPage,
    },
    {
      path: "/admin/:id",
      component: ShowAnswersPage,
    },
    {
      path: "/:id/",
      component: CreateAnswerPage,
    },
  ],
});
