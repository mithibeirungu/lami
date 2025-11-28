<template>
  <section class="container" style="padding: 80px 0 120px 0; max-width: 800px">
    <article class="about-article">
      <h1>About LAMI</h1>
      <p class="about-lead">
        LAMI is a blog dedicated to sharing detailed information and stories about remarkable cars. 
        Whether you're a car enthusiast, collector, or simply curious about automotive history and engineering, 
        this is a place to discover, learn, and discuss.
      </p>
      
      <div class="about-content">
        <h2>What We Do</h2>
        <p>
          We curate and publish articles about cars, covering everything from classic collectibles to modern 
          performance machines. Each article includes detailed specifications, descriptions, and insights to help 
          readers understand what makes each vehicle special.
        </p>

        <h2>For Readers</h2>
        <p>
          Browse our collection of car articles, use the search feature to find specific models or topics, 
          and read in-depth information about vehicles that interest you. You can also leave comments and 
          share your thoughts on any article.
        </p>

        <h2>For Contributors</h2>
        <p>
          If you're registered, you can create new articles about cars. Simply log in and use the "Write" 
          button to add information about vehicles you want to share with the community.
        </p>
      </div>
    </article>
  </section>
</template>

<style scoped>
.about-article {
  line-height: 1.8;
}

.about-article h1 {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 24px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--border);
}

.about-lead {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 40px;
  line-height: 1.7;
}

.about-content h2 {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 40px 0 16px 0;
  color: var(--text);
}

.about-content p {
  color: rgba(255, 255, 255, 0.85);
  margin-bottom: 24px;
  line-height: 1.8;
}
</style>
