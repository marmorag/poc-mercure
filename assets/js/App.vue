<template>
    <div>
        <h1>La liste des tracks</h1>
        <table>
            <thead>
                <tr>
                    <th>title</th>
                    <th>author</th>
                    <th>burn</th>
                    <th>action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(track, id) in tracks" :key="id">
                    <td>{{ track.name }}</td>
                    <td>{{ track.author }}</td>
                    <td>{{ track.burn }}</td>
                    <td>
                        <a href="#" @click="onTurnUp(track)">+</a>
                        <a href="#" @click="onTurnDown(track)">-</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'App',
        data() {
          return {
              tracks: {},
          };
        },
        created() {
            this.fetchTracks();
        },
        methods: {
            async fetchTracks() {
                const response = await axios.get('http://localhost:8000/api/tracks');
                response.data['hydra:member'].forEach(track => {
                    // use $set to keep property reactive
                    this.$set(this.tracks, track['@id'], track);
                });
                this.subscribeToHub(response);
            },
            subscribeToHub(response) {
                const hubUrl = response.headers.link.match(/.*<(http:\/\/.*)>.*rel="mercure".*/)[1];
                const url = new URL(hubUrl);
                url.searchParams.append('topic', `http://${window.location.host}/api/tracks/{id}`);

                this.eventSource = new EventSource(url);

                this.eventSource.onmessage = e => {
                    const updatedTrack = JSON.parse(e.data);
                    // use $set to keep property reactive
                    this.$set(this.tracks, updatedTrack['@id'], updatedTrack);
                };
            },
            onTurnUp(track) {
                track.burn++;
                axios.put('http://localhost:8000'+track['@id'], track)
            },
            onTurnDown(track) {
                track.burn--;
                axios.put('http://localhost:8000'+track['@id'], track)
            },
        }
    }
</script>
