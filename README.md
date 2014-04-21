# Interaktive JS

Use JS and your hands to manipualte 3D models.

## Input

We use [Leap Motion](https://www.leapmotion.com/) to capture your hands movements and feed that data to our JS API.

### Actions

The following actions are supported:

* Rotation: Swipe your right or left hand with an open palm to rotate the model on screen.
We use the direction and velocity of your palm to calculate the intensity
and number of degrees for the rotation movement.
* Scaling: Push your hand forward and backward to (into and away from the screen) to zoom in or out off the model.
Again we use the velocity of your movement to scale a little or a lot.
* Translation: Swipe your right or left hand upwards or downwards to "push" the model down or up.
This is an inverse Y-axis movement, so an upwards movement will "push" the model downwards.

We use the velocity of your hands movements to calculate the intensity of each action.

## How to run

Interaktive JS works with **.obj** 3D models files. Create a directory with the following structure:

    - /myExample
      - myExample.html
      - /models
        - car.obj
        - shield.obj
        - cube.obj

1. Download and use Interaktive JS API in your HTML document

  ```javascript
  <script src="interaktive.min.js"></script>
  ```

1. Connect your Leap Motion device

1. Open your HTML document in your browser (tested on Chrome and Safari but will likely work on all modern web browsers)

You'll see on-screen instructions to load **.obj** files found under the `models` directory living in the same directory as your HTML document.

After you load a 3D model you can manipulate it with the movements described under the 'Actions' section.
