# EasyPractice Technical Challenge

I'm familiar with Git, but not so much with Laravel. I used it many years ago, you can even see when I forge it, but never contributed there. I did some applications though, again, many years ago. Respuect Vue.js, I just played a little bit with it before.

So to me 3 hours was like rushing and using all my neuron.

## Task resolution order and feelings
- ✅ [Solves (BUG) I created some seeders that you can run with php artisan db:seed, but it gives an error. Can you make it work?](https://github.com/revuel/ep-techchallenge/commit/95c21a659b241edcdba81eb110c087d62048d7ca)
Ok, nothing to comment, just a typo.

- ✅ [Solves (BUG) For some reason, the client bookings are not showing up in the front-end. Can you fix that?](https://github.com/revuel/ep-techchallenge/commit/7ca4ab7b5d02bbd7d8a061464f96b95cb4ea35bd)
The controller wasn't prepare to get the bookings. 

- ✅ [Solves (BUG) The list of bookings displayed on a client page has unformatted dates. Can you make sure they look something like this: Monday 19 January 2020, 14:00 to 15:00](https://github.com/revuel/ep-techchallenge/commit/97755c19236127fe5ac12ef3cd8d010388996c38)
Ok this one is interesting. I just relied on locales and make sure jest used UTC. Of course, there are better options to handle date and datetimes. Probably by including some libraries.
Worth to mention that in my opinion, this is just a visualization issue, so the implementation is in place at the frontend only. Who knows who else could be using the API? 🤷‍♂️

- ✅ [Solves (BUG) When trying to delete a client, the front-end does not update. Can you improve the experience, so the user knows the client was actually deleted?](https://github.com/revuel/ep-techchallenge/commit/442a26c3152b4d35c93bb63ea017412a0884d70d)
This was a bit tricky, I ended up doing a local deep copy of the clients and after a succesful deletion, then update this local copy so the page actually refreshes. Unsure if this is the best way.

- ✅ [Solves (FEATURE) The client bookings are currently displayed in random order. Please display them in chronological order, newest first.](https://github.com/revuel/ep-techchallenge/commit/708e558a8d9797a2a569d86252a9b2e9a2cb7e2a) 
Nothing to add here, just added an orderby on the controller.

- ✅ [Solves (FEATURE) Users want a quick way to see future and past bookings. When viewing client bookings, can you make a dropdown with three values - "All bookings", "Future bookings only" and "Past bookings only". Make it so that selecting an item from the dropdown would only show bookings that apply to the selected filter. When the page loads, default to "All bookings"](https://github.com/revuel/ep-techchallenge/commit/a694cfb98829e3ca2edf8191c6a6c6b1709f621d)
Just a matter of visualization, again. Note that the tests will use different dates each different day are executed, and they will work

- ✅ [Solves (FEATURE) We noticed users started entering random data when creating clients. We should include some validation. Make sure that, when creating a client: [...]](https://github.com/revuel/ep-techchallenge/commit/24c2b0a6bba3bcbe4a93b80af1df85704cb4a0d2)
So here I just added the validations on the backend. After checking, I decided to include a StoreClientRequest for ClientsController, so the validation is there.
I left the frontend just to inform about the errors when they happen. But validations there should be present also.
I didn't check the seeds, and database cleaning would be another topic. 

- ✅ [Solves (FEATURE) Currently, any logged-in user can view all of the system's clients, including those created by other users. Users are obviously not happy with that. Can you make it so that a single Client only belongs to one User?](https://github.com/revuel/ep-techchallenge/commit/1237387a1c1fb2a9c94b75aed738d12bc4e32326)
Well, sounds more like a security bug to me but ok. I left it for the end just because it would require migrations and a little bit more focus I would say. By the way, you already had the implemntation commented on the ClientSeeder


## Thank You!
Thank you too!
