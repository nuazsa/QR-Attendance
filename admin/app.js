var app = new Vue({
  el: '#app',
  data: {
    scanner: null,
    activeCameraId: null,
    cameras: [],
    scans: []
  },
  mounted: function () {
    var self = this;
    self.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5 });
    self.scanner.addListener('scan', function (content, image) {
      const scanData = { date: +(Date.now()), content: content };
      self.scans.unshift(scanData);
      self.sendScanToServer(scanData);
    });
    Instascan.Camera.getCameras().then(function (cameras) {
      self.cameras = cameras;
      if (cameras.length > 0) {
        self.activeCameraId = cameras[0].id;
        self.scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function (e) {
      console.error(e);
    });
  },
  methods: {
    formatName: function (name) {
      return name || '(unknown)';
    },
    selectCamera: function (camera) {
      this.activeCameraId = camera.id;
      this.scanner.start(camera);
    },
    sendScanToServer: function (scanData) {
      fetch('save_scan.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(scanData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          console.log('Scan data successfully sent to the server');
        } else {
          console.error('Error sending scan data to the server:', data.message);
        }
      })
      .catch(error => {
        console.error('Error sending scan data to the server:', error);
      });
    }
  }
});