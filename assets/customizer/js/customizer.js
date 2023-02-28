// var $printify = $.noConflict();
// $(document).ready(function() {

  window.onload = function() {
      if (window.jQuery) {
  var $printify = $.noConflict();
  $printify(document).ready(function($) {
 var shop = window.Shopify.shop;

	function _customize_controls(canvas) {
			fabric.Canvas.prototype.customiseControls({
					tl: {
							action: 'move',
							cursor: 'grab',
					},
					tr: {
							action: 'scale',
					},
					bl: {
							action: 'remove',
							cursor: 'pointer',
					},
					br: {
							action: 'scale',
					},
					mb: {
							action: 'moveDown',
							cursor: 'grab',
					},
					mt: {
							action: 'rotate',
							cursor: 'pointer',
					},
					mtr: {
							action: 'rotate',
							cursor: 'pointer',
					},
			});
			fabric.Object.prototype.customiseCornerIcons({
					settings: {
							borderColor: '#000',
							cornerSize: 25,
							cornerBackgroundColor: '#fff',
							cornerShape: 'circle',
							cornerPadding: 10
					},
					tl: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAQAAACQ9RH5AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADdcAAA3XAUIom3gAAAAHdElNRQfjAhwJEDKxzwCVAAAB+0lEQVRYw+3ZPU8CMRjA8T++DPAJTNyZXfwCxgRZHWCFaHzZdXGTwcQYNzEx0cDoVzDx5QM4a6Izl7i4agzKOcBB6V31OWyriTwMJKX0d+31npYCo0aZgBalkb8/YlT5ICSkw8ZvsJ5plfVI66wnOon1QJtYx/RXrEP6O9YRvSRgQ0I+KMganBDCFWHNCSp24Qfx2DzKqmWEzWU5YJFpAHLMaJ8+8QJAm0u2eRVfZMooxu5sMX0j0qG2HmP4f8JLnFMja6X9LDXOZdksyslHxhppHqd6L5FWpWzInRX4vp/DNXp4qKuc9kumrQz1VN85G165VFhl7UeGY5UeQG7ZGB0NRZzNsWhoYC6hpG2om9PoOm80BgWS3YWtl7JL8ckqdNkz2324yhlazDqdUskR/FquniRgWbwBshUd1gAq3ifXenQFPukeGw1yPIEExh3yPHtayQ63hrpNbep2WFUTSFKvzfvjNKvTo2mQB71sskLH6ZQK2eQkDrumh9jBIhHRmf69bqdp1RjvvffYvdUTSIPVXq9vrMDXyaze4y4dUOWBfSvwNs/kaXJho7Hxb6cx/Ldh+VHEIQuCo4grtuweRdTEy96urEHpUOfFlyivKYqC7QM2eUh2Kcp665N2xH5HO2S/oh2zJtoDm0R7YnXaI6vSnlmAEq2f/MX3CVa5pc29ifuZAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTAyLTI4VDA4OjE2OjUwKzAxOjAwYUYBnQAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wMi0yOFQwODoxNjo1MCswMTowMBAbuSEAAAAZdEVYdFNvZnR3YXJlAHd3dy5pbmtzY2FwZS5vcmeb7jwaAAAAAElFTkSuQmCC',
					},
					tr: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAIfSURBVGje7Zm5L0RRFIc/sSVohvgDKGyJRqWzNNZISGyNoLf9CRJbCKIQBZHoJUNkWqEVBYmoEUQlKlvEc81gvNneneSed5s5t5op3vfl5dzfO/c98LsCNDHFDmc8ckipX9hyephmnysc11qQhOZRxyhrHPMUg42uDRl0u7rF57wnxUbXmAS+SwP8uxokBLbTEAhICExq469lOiCHPU2BA6n+zyWoJTAjtwX1FPokU2BJQ6BaDj+rgX8m2ybe4cQu3mHLLt5h3C7eodEu3qHELv7GNH7RE/nh+hUyix/yxN9T60rHebMC6574qpiA7jUrUM+rJz7yjNgN/3NBgeke6EiqEMV/VxYtDFAokQGtvCTAP1Dj38wfr+ArPl7Bd7xbwQo+0o7fCrdUYq3K1S4vJlOZypTRKqFfbS5rValixVHx0mEHX8HdT7i+0WUTb0XBjfddIR4vqFDEIG1qYIpWlRqmEg9Zr+bbsZDL8KV31djohY8o1JsV6P27dDCskBov8Ipx/t+lg+oIce950Bg2KxBKcYhKtFZd3WKgbtM6WK6aT3qreGhOA78ikQGTdvH6r5eXpSL31C4+m2ebeKjWwC9JPvMGNARC5MsJzGl1gKDCgeYe2CNHRuBaOwUmJPCBNFJwU0KgQRv/SaeEwJgG+p0zdmiR6YCNpNgnjlhjhDryJFNgIQZ7pbp9mm7K/Bq9SznkMXyLp2iS+ZKZqr4AJ0S0euK7DacAAAAldEVYdGRhdGU6Y3JlYXRlADIwMTktMDItMjhUMTI6MzA6MTIrMDE6MDCAsVCYAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE5LTAyLTI4VDEyOjMwOjEyKzAxOjAw8ezoJAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAASdEVYdEVYSUY6T3JpZW50YXRpb24AMYRY7O8AAAAASUVORK5CYII='
					},
					bl: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAHdElNRQfjAhwJDwZdIfq+AAAB3ElEQVRo3u2ZPyxDQRzHP8UrUSGCSERi6EgqMRqQSCoSW0cbs02DqQizhEEMRCSdLAabBRFsYrHZJCUS/4Ki0VruVV/76J2855a7G365311/30/e793vNXcg1wKs8kJOsj+xRkAysmTrlxa3+5Bc4ApJgDZl5Ha5ZVWKYd95LbOilmqVgKoAScbLrFhnTCWgbAp8a9oB3FLQSR/1Rb5uYSNMlYkYEXaY5qKZRw65KAcUZJOs8paT7Vk2CP4OsOybuN2XnILOetXEtfK+UG0ZWrn/HjrlImJ8RdIH6VHaAYtu9n8CqBX2nGkfALpEdQwVOrVvQ+0A7q9clDsftOrkASwa/+sJaE+BdgBnIbJEnmaIA5BgxQONCeYAWCEBwDOZ70nnO5ARNSotxunCmvXnZkd7c4umPQXaAeQ/PQ0MAPDAQd43QiUAu3wKTy8tABxz6zVAmB0AzujJ+7apASCU/6s6zyAAUfbkwmpPgQEwAAbAABgAA2AADIABMAAGwAAYAANgAOTPB1LiqClV4FvAAig4dNriCIBLPwBmS3yLJZ4t1SegPQXuADlhLU807Cg5t0n3FNwIO8+kBwD2BVhK/icdfHh+V5QhrEId9xxA+Q4mxilpT6TTnBD7SeYL2iZBHp62e9EAAAAldEVYdGRhdGU6Y3JlYXRlADIwMTktMDItMjhUMDg6MTU6MDYrMDE6MDChQYHAAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE5LTAyLTI4VDA4OjE1OjA2KzAxOjAw0Bw5fAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAAASUVORK5CYII=',
					},
					br: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAHdElNRQfjAhwJEiEHRyPJAAAB6UlEQVRo3u3YvytFYRzH8ff1M+EaLMTCKIPCQlkMNv+BjGRVN4tiVPIjZmKiJGZKDMKAxUBRJmW4XSk/8uNYruSe87jPt77PeUr3c7b7PPd8X+c853nO00lQzgjtlGGTe1Y5seopyAqB4HilRRvwIAIEpHTLF5EU/kPaPy/AcwoAOeDTN+BS+x7IJuEWJT4Ax3TQTDO12sVtAUMuCksAh1T4BQTsU+UX4JBgPwMcESST0AlBtg44IESVueLASNjVnhHhEmkqgLG4COECp9mW0XgGwgyIifASse79xDwQ61qAndCpp361mwn1OoBG1rjkOntcME9lTo9owhs1Wvcgf6II4/GVh/DjOBNv+VyCh/IAKT4JCJjWPGlC1LubPs7Z9HP9hfzXFCufr58BirjxczEJFrPrxISf8gvGF1rs5T0Q5iJeV5Pxle8y7BhyCUmWuCWdPe7YpkkHMGjctPwmzIba93QADWSsCEeh1hetQejlyYJwGtGqlh4ejYTvGeEUkP8uVEd+lVXNX4QzbiN/V85fAxHEAZATHERGcBIJwVHsCc7Sy7NfAGzYANx9rq+jzeXVmZJklTvSpHn38wxMCZch9SHolP5BG1DqGyBOAaANkO7xMtoA6S53XfaFJH9KGabVsu8HZyx/AeMBM6yEpdbEAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTAyLTI4VDA4OjE4OjMzKzAxOjAwiAgiNAAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wMi0yOFQwODoxODozMyswMTowMPlVmogAAAAZdEVYdFNvZnR3YXJlAHd3dy5pbmtzY2FwZS5vcmeb7jwaAAAAAElFTkSuQmCC',
					},
					ml: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAHdElNRQfjAhwJEQxptSx/AAABIElEQVRo3u2WMW7CQBBFnwOniJQCagQtOQIVR4CKVBiOgtKl5wxwoCiRUkRIFFTImwKDjDHGSLNeifznwlprPP97PNodEEIIIYQQ/51G4dMeA7ZsDHXaDEn4qRY8ZY9jR99M/pUdjj3TKsExCQ6HY2lmYJlmTIhvhb6d5B0rMwOrU86EWVV5PwZKLZzL+zJw9Ufk5f0ZOLPQTO8xC6Lcax0+jAx0cuuIBfBe9vX+r0wVQshnLIwCyR8sjCK+eDZrtvv5fgooDkCDX4YX/V8X7tAFk7BNGMpCKn8sftFG9MnaqNQDXi6KP89uRMVVqGUrLrNQ82EEdR3HpUOJ/4Hk5kwUeCQDH0Np/56hFKDLmJaZPECbMV3TjEIIIYQQ4mH4A5tT28kDqjq5AAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTAyLTI4VDA4OjE3OjEyKzAxOjAwnVF1cAAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wMi0yOFQwODoxNzoxMiswMTowMOwMzcwAAAAZdEVYdFNvZnR3YXJlAHd3dy5pbmtzY2FwZS5vcmeb7jwaAAAAAElFTkSuQmCC',
					},
					mb: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAQAAACQ9RH5AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADdcAAA3XAUIom3gAAAAHdElNRQfjAhwJEDKxzwCVAAAB+0lEQVRYw+3ZPU8CMRjA8T++DPAJTNyZXfwCxgRZHWCFaHzZdXGTwcQYNzEx0cDoVzDx5QM4a6Izl7i4agzKOcBB6V31OWyriTwMJKX0d+31npYCo0aZgBalkb8/YlT5ICSkw8ZvsJ5plfVI66wnOon1QJtYx/RXrEP6O9YRvSRgQ0I+KMganBDCFWHNCSp24Qfx2DzKqmWEzWU5YJFpAHLMaJ8+8QJAm0u2eRVfZMooxu5sMX0j0qG2HmP4f8JLnFMja6X9LDXOZdksyslHxhppHqd6L5FWpWzInRX4vp/DNXp4qKuc9kumrQz1VN85G165VFhl7UeGY5UeQG7ZGB0NRZzNsWhoYC6hpG2om9PoOm80BgWS3YWtl7JL8ckqdNkz2324yhlazDqdUskR/FquniRgWbwBshUd1gAq3ifXenQFPukeGw1yPIEExh3yPHtayQ63hrpNbep2WFUTSFKvzfvjNKvTo2mQB71sskLH6ZQK2eQkDrumh9jBIhHRmf69bqdp1RjvvffYvdUTSIPVXq9vrMDXyaze4y4dUOWBfSvwNs/kaXJho7Hxb6cx/Ldh+VHEIQuCo4grtuweRdTEy96urEHpUOfFlyivKYqC7QM2eUh2Kcp665N2xH5HO2S/oh2zJtoDm0R7YnXaI6vSnlmAEq2f/MX3CVa5pc29ifuZAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTAyLTI4VDA4OjE2OjUwKzAxOjAwYUYBnQAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wMi0yOFQwODoxNjo1MCswMTowMBAbuSEAAAAZdEVYdFNvZnR3YXJlAHd3dy5pbmtzY2FwZS5vcmeb7jwaAAAAAElFTkSuQmCC',
					},
					mt: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAQAAACQ9RH5AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADdcAAA3XAUIom3gAAAAHdElNRQfjAhwJESPCZBEmAAADNklEQVRYw+2ZS0hUURjHf6P5iNSek2KmhEgbW1hmga9qUbQQgpYtMrKoKaOXCEKQSUlFtKilCyNyIZUFPTCjrFDEXpuilxBIGoaK0pTPmdvijs3rnHvPvY7RYv7f7p5vvt/9zus794wDK8qigHxyyCSDeBbiwc0ofXzhE510M24pmpLyuchHNEMb4zF7WRwpZBwVvDFBBto4N8gziFfEcYrMoA7K+WoB6rdHrBVGPIMXDS8uI+waOmxBdfPQgDMk4rm/rf1y7H4mZoHVbYCygIh1QW1CLeDWrKG6eblMLAAXQloESuNlhLC6tZLC2bCnYVpBT0SxepdrZmAn7yOOFZojCJvIc9YbLjCNLtp5yzuGGWWaZNLIZh3FlBBntjrlum74ln1UkSH97VIO8dlCzgEqN3AbxEWC6YvHsJs+q+BMRqROTSxX7rUUrlkDy1buFActD1kFk6rgUknzb7bami1NquBOSbZltrB1plgfuFjSeGDOsD5wi7DpzhxiNQCncCoMhxW1SGI1gEphQ70NbK0yVgN4Ing8QbplbBFedXAMSRQKgjQZnREk2oBD2XcStgjex0uujY62knErOMO29mmu2MACuOhXgE7QalBsoooqqqj+Y2XQpvRd+N34m9ZAV5kOqQTNAG3KG7tXWMfMlCsoHW4cMZQoh3Cw0Qb4hKBYPkPDwpnBTsbpwmE8AlbAtTbyrRfE8ZBlBVxnA+tkWBCpXW+cOyzcFcbaow62hz0sWZYJqmB72B0hq3fGamYczLC3bWG3MybJN0kV7KHawrFVV6UkW419fieVMb5PmjI0lWZpnBe+Oy9lsMYIVcw3hSbiYlAaw012oLP6BjLAaVZJoZlUm5yqdwW6O9CwIo3XPKWLHr7xk1gWsYRc8thMgclMuMTJ0FD/wm4yLzyH8C6NNLZFdPkW6nSeZB5GFNsovvMLdtJ3qXgaIgT1cEo29sHZ+rWToVljf7BNPtv8SyB0T15psBWYm5dGlhlNc5fv7wlxmS+lyxa2Q+VIVcgxw0PNJu7hsTCqD2zeBgqVzlE6mDJBdlKjH2vMZa3uJFFEAavJIZVkUvDwCze99PKBV3QzpB7qD2VkGaE2XbL4AAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTAyLTI4VDA4OjE3OjM1KzAxOjAwGtNMgwAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wMi0yOFQwODoxNzozNSswMTowMGuO9D8AAAAZdEVYdFNvZnR3YXJlAHd3dy5pbmtzY2FwZS5vcmeb7jwaAAAAAElFTkSuQmCC',
					},
					mr: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAHdElNRQfjAhwJEQxptSx/AAABIElEQVRo3u2WMW7CQBBFnwOniJQCagQtOQIVR4CKVBiOgtKl5wxwoCiRUkRIFFTImwKDjDHGSLNeifznwlprPP97PNodEEIIIYQQ/51G4dMeA7ZsDHXaDEn4qRY8ZY9jR99M/pUdjj3TKsExCQ6HY2lmYJlmTIhvhb6d5B0rMwOrU86EWVV5PwZKLZzL+zJw9Ufk5f0ZOLPQTO8xC6Lcax0+jAx0cuuIBfBe9vX+r0wVQshnLIwCyR8sjCK+eDZrtvv5fgooDkCDX4YX/V8X7tAFk7BNGMpCKn8sftFG9MnaqNQDXi6KP89uRMVVqGUrLrNQ82EEdR3HpUOJ/4Hk5kwUeCQDH0Np/56hFKDLmJaZPECbMV3TjEIIIYQQ4mH4A5tT28kDqjq5AAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTAyLTI4VDA4OjE3OjEyKzAxOjAwnVF1cAAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wMi0yOFQwODoxNzoxMiswMTowMOwMzcwAAAAZdEVYdFNvZnR3YXJlAHd3dy5pbmtzY2FwZS5vcmeb7jwaAAAAAElFTkSuQmCC',
					},
					mtr: {
							icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAQAAACQ9RH5AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAADdcAAA3XAUIom3gAAAAHdElNRQfjAhwJESPCZBEmAAADNklEQVRYw+2ZS0hUURjHf6P5iNSek2KmhEgbW1hmga9qUbQQgpYtMrKoKaOXCEKQSUlFtKilCyNyIZUFPTCjrFDEXpuilxBIGoaK0pTPmdvijs3rnHvPvY7RYv7f7p5vvt/9zus794wDK8qigHxyyCSDeBbiwc0ofXzhE510M24pmpLyuchHNEMb4zF7WRwpZBwVvDFBBto4N8gziFfEcYrMoA7K+WoB6rdHrBVGPIMXDS8uI+waOmxBdfPQgDMk4rm/rf1y7H4mZoHVbYCygIh1QW1CLeDWrKG6eblMLAAXQloESuNlhLC6tZLC2bCnYVpBT0SxepdrZmAn7yOOFZojCJvIc9YbLjCNLtp5yzuGGWWaZNLIZh3FlBBntjrlum74ln1UkSH97VIO8dlCzgEqN3AbxEWC6YvHsJs+q+BMRqROTSxX7rUUrlkDy1buFActD1kFk6rgUknzb7bami1NquBOSbZltrB1plgfuFjSeGDOsD5wi7DpzhxiNQCncCoMhxW1SGI1gEphQ70NbK0yVgN4Ing8QbplbBFedXAMSRQKgjQZnREk2oBD2XcStgjex0uujY62knErOMO29mmu2MACuOhXgE7QalBsoooqqqj+Y2XQpvRd+N34m9ZAV5kOqQTNAG3KG7tXWMfMlCsoHW4cMZQoh3Cw0Qb4hKBYPkPDwpnBTsbpwmE8AlbAtTbyrRfE8ZBlBVxnA+tkWBCpXW+cOyzcFcbaow62hz0sWZYJqmB72B0hq3fGamYczLC3bWG3MybJN0kV7KHawrFVV6UkW419fieVMb5PmjI0lWZpnBe+Oy9lsMYIVcw3hSbiYlAaw012oLP6BjLAaVZJoZlUm5yqdwW6O9CwIo3XPKWLHr7xk1gWsYRc8thMgclMuMTJ0FD/wm4yLzyH8C6NNLZFdPkW6nSeZB5GFNsovvMLdtJ3qXgaIgT1cEo29sHZ+rWToVljf7BNPtv8SyB0T15psBWYm5dGlhlNc5fv7wlxmS+lyxa2Q+VIVcgxw0PNJu7hsTCqD2zeBgqVzlE6mDJBdlKjH2vMZa3uJFFEAavJIZVkUvDwCze99PKBV3QzpB7qD2VkGaE2XbL4AAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE5LTAyLTI4VDA4OjE3OjM1KzAxOjAwGtNMgwAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOS0wMi0yOFQwODoxNzozNSswMTowMGuO9D8AAAAZdEVYdFNvZnR3YXJlAHd3dy5pbmtzY2FwZS5vcmeb7jwaAAAAAElFTkSuQmCC',
					}
			}, function() {
					canvas.renderAll();
			});
	}


	function make_base_auth(user, password, shopify) {
			var tok = user + ':' + password;
			var hash = Base64.encode(tok);
			return "Basic " + hash;
	}
	var shopifytoken = Base64.encode(JSON.stringify(window.Shopify));
	var auth = make_base_auth(shopifytoken, '912ff229790c494cb062ec4152fc2f56');
	$('.checkbox_click').prop('checked', false);

	function checkImageExists(imageUrl, callBack) {
		 var imageData = new Image();
		 imageData.onload = function() {
		     callBack(true);
		 };
		 imageData.onerror = function() {
		     callBack(false);
		 };
		 imageData.src = imageUrl;
	}

	$("#file").change(function(){
    var files = document.getElementById('file').files;
		if (files.length > 0) {
		 getBase64(files[0]);
	 }
  });

	function getBase64(file) {
	   var reader = new FileReader();
	   reader.readAsDataURL(file);
	   reader.onload = function () {
	     var baseImage = reader.result;
			 // var canvas = window['front-canvas'];
       var canvas = window[window['current-canvas']];
			 addImagetoCanvas(canvas,baseImage);
			 $(".img_customization").show();
			 $('#product_details .nav-click').eq(4).click();
	   };
	   reader.onerror = function (error) {
	     console.log('Error: ', error);
	   };
	}

	function flip() {
		var checkClass = $('.front-image').hasClass('flip-front');
		if (checkClass) {
			$('.rotateButton').click();
		}
	}

	function addImagetoCanvas(canvas,img_url) {
		// flip();
		var newID = (new Date()).getTime().toString().substr(5);
		fabric.Image.fromURL(img_url, function(myImg) {
			var img1 = myImg.set({ left: 0, top: 0 ,width:150,height:150,myid: newID,objecttype: 'image'});
			canvas.add(img1);
			canvas.renderAll();
			SetActiveObject(canvas,newID);
		}, {
				crossOrigin: 'anonymous'
		});
	}

		function hideImgCustom() {
			$(".img_customization").hide();
			$("#ImgCustom").hide();
		}
		function showImgCustom() {
			$(".img_customization").show();
			$("#ImgCustom").show();
		}





	$('#loadmorebtn').click(function(event){
		$('#imgurl').toggle();
		$('#loadmorebtn').toggle();
	});
	$('.checkbox_click').click(function(){
		if($(this).prop("checked") == true){
			$('.show_btn').show();
		}
		if($(this).prop("checked") == false){
			$('.show_btn').hide();
		}
	});
	$(".load_img").click(function(event){
     $(".upload").click();
  });


	$('#btn-url').on("click",function(event){
		var img_url = $('.load-url-img').val();
		checkImageExists(img_url, function(existsImage) {
	 	 if(existsImage == true) {
			 var canvas = window[window['current-canvas']];
			 addImagetoCanvas(canvas,img_url);
			 $('.load-url-img').val('');
			 $(".img_customization").show();
			 $('#product_details .nav-click').eq(4).click();
			 $('#load-url').show();
			 $('#imgurl').hide();
			 $('#loadmorebtn').toggle();
	 	 }else {
		 	$('#fileextension-error').fadeIn(1000).fadeOut(5000);
	 	 }
	  });

	});

	function UploadCanvasImages(){
		var SendableObject = {};
		$('.UCanvas').each(function(ind,texarea){
			SendableObject[$(texarea).attr('id')]=$(texarea).val();
		})
		var url = appUrl+'Api/insert_canvas';
			$.ajax({
				beforeSend: function(xhr) {
						xhr.setRequestHeader('Authorization', auth);
				},
				url:url,
				data:SendableObject,
				method:'POST',
				dataType:'json',
				success:function(UploadedImages){
						var color = $('.colorBox.active').data('color');
						var size = $('.sizeBox.active').data('product_size');
						var SelectedVariantId = $('#SelectedVariantId').val();
						var _Props = {};
						for (imgs in UploadedImages){
							_Props[imgs]=UploadedImages[imgs]
						}
						_Props['color'] = color;
						_Props['size'] = size;
					var SaveObject = {quantity: 1,  id: SelectedVariantId, properties: _Props };
					$.ajax({
						'url':'/cart/add.js',
						'type':'post',
						'dataType':'json',
						'data':SaveObject,
						'success':function(cart){
								$("#loader-main").hide();
								window.location.href = '/cart';
						}
					})
				}
			})
	}
	function saveForCart(){
		$("#loader-main").show();
		var CanSelector=['front-canvas','back-canvas'];
		$('html,body').scrollTop(0);
		var canvaschecker;
		for (canvaschecker = 0; canvaschecker < CanSelector.length; canvaschecker++) {
			window[CanSelector[canvaschecker]].deactivateAll();
			window[CanSelector[canvaschecker]].renderAll();
			_UpCanvas = $('#'+CanSelector[canvaschecker]);
				html2canvas([_UpCanvas.get(0)], {
						onrendered: function(canvas) {
								var Base64Data = canvas.toDataURL('image/png', 1.0);
								var _CName = 'back_canvas';
								if (canvaschecker == 2) {
									_CName = 'front_canvas';
									canvaschecker--;
								}
								var CanName = '<textarea  style="display:none;" class="UCanvas" id="'+_CName+'">'+Base64Data+'</textarea>';
								$('#product_details center').append(CanName);
								if(canvaschecker == 1) {
									UploadCanvasImages();
								}
						}
				});
		}
	}

	$('#btn_save').on("click",function(event){
		var check = $('.sizeBox').hasClass('active');
		if (check) {
			saveForCart();
		}else{
			$('#chooseSize').fadeIn(1000).fadeOut(5000);
		}
	});

  $(document).on('click','#cliparts-images .card',function() {
      var url = $(this).attr('data-src');
      // var canvas = window['front-canvas'];
      var canvas = window[window['current-canvas']];
      addImagetoCanvas(canvas,url);
      $(".img_customization").show();
      $('#product_details .nav-click').eq(4).click();
      // $(".hide_cliparts").hide();
  });


	$('#search').click(function(event){
		var url = appUrl+'Api/search_category';
		var search_data = $('.searchInput').val();
		$.ajax({
			beforeSend: function(xhr) {
					xhr.setRequestHeader('Authorization', auth);
			},
			url: url,
			method: 'POST',
			data: {data:search_data},
			success: function(data){
				var ordata = JSON.parse(data);
				var html = '';
				$.each(ordata,function(index, value) {
					var cat_name = value.cat_name;
					var cat_image = value.cat_image;
					html += '<div data-src="' + cat_image + '" class="col-md-3 card child_cat" data-id=' + value.id + '>';
					html += '<div class="categoryButton">\
													<img class="card-img-top" src="'+appUrl+'/assets/images/' + cat_image + '>\
													<div class="card-text">' + cat_name + '</div>\
											</div>';
					html += '</div>';
					$('#parent-category').html(html);
				});
				$('.child_cat').click(function() {
						$('#search-data').hide();
						var cat_id = $(this).data('id');
						var cat_title = $(this).data('title');
						get_cliparts(cat_id, cat_title);
				});
			}
		});
	});

	get_all_product();
	getPid();

	function getPid() {
		var url_string = document.URL;
		var url = new URL(url_string);
		var pid = url.searchParams.get("pid");
		return pid;
	}




	function get_all_product() {
		$.ajax({
				beforeSend: function(xhr) {
						xhr.setRequestHeader('Authorization', auth);
				},
				url: appUrl+'Api/get_product/'+getPid(),
				type: 'get',
				dataType: 'json',
				contentType: "application/json",
				success: function(response) {
						single_product(response);
						$('.upload-list a').click(function(e) {
								e.preventDefault();
								var id = $(this).attr('href');
								$('.upload-list').hide();
								$('.upload-grid').show();
								$('.upload-option').hide();
								$(id).addClass('active').show();
								if ($(this).hasClass('clipart')) {
										clipart_categories(auth);
								}
								if ($(this).hasClass('myimages')) {
										ShowUploadedImage(auth);
								}
						});
						$('.myimages-grid').click(function() {
								ShowUploadedImage(auth);
						})
						$('.clipart-grid').click(function() {
								clipart_categories(auth);
						})
						$('.upload-grid a').click(function(e) {
								e.preventDefault();
								var id = $(this).attr('href');
								$('.upload-option').hide();
								$(id).addClass('active').show();
								if ($(this).hasClass('myupload-grid')) {}
						});
				},
				error: function(response) {
					$('#loader-main').hide();
					$('#mainContainer').empty();
					$('#mainContainer').html('<div class="container"><h2>This product is not available for customization !</h2></div>');
				}
		});
	}

    function ShowUploadedImage(auth) {
        $('#loader-main').show();
        if ($("#customer_email").length > 0) {
            $.ajax({
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', auth);
                },
                url: appUrl+'Api/GetMyImages',
                type: 'get',
                dataType: 'json',
                data: {
                    customer_id: $("#customer_id").val()
                },
                contentType: "application/json",
                success: function(r) {
                    $('#loader-main').hide();
                    if (r.length > 0) {
                        var myimages = '';
                        myimages += '<div class="row m-0 category-tab" >';
                        $(r).each(function(index, data) {
                            myimages += '<div class="col-md-3 card"  data-src="" class="uploaded_images" >';
                            myimages += '<div class="image_class" >';
                            myimages += '<img style="max-height: 109px;" class="card-img-top" src="'+appUrl+'assets/user_images/' + data.image + '">';
                            myimages += '</div>';
                            myimages += '<div class="image-overlay">';
                            myimages += '<button type="button" class="btn btn-info add_my_image" image_url = "'+appUrl+'assets/user_images/' + data.image + '"><i class="fa fa-plus"></i></button>';
                            myimages += '<button type="button" class="btn btn-danger delet_my" my_image_id="' + data.id + '" image_name="' + data.image + '"><i class="fa fa-trash"></i></button>';
                            myimages += '</div>';
                            myimages += '</div>';
                        });
                        myimages += '</div>';
                        $('#Myimages').html(myimages);
                        $('#clipart-breadcrumb ol').html('My Images');
                        $('.add_my_image').click(function() {
                          var url = $(this).attr('image_url');
													// var canvas = window['front-canvas'];
                          var canvas = window[window['current-canvas']];
													addImagetoCanvas(canvas,url)

                        });
                        $('.delet_my').click(function() {
                            swal({
                                title: "Are you sure?",
                                text: "To delete your uploaded image?",
                                icon: "warning",
                                buttons: ["No", "Delete"],
                                dangerMode: true,
                            }).then((willDelete) => {
                                if (willDelete) {
                                    var delete_id = $(this).attr('my_image_id');
                                    var image_name = $(this).attr('image_name');
                                    var delete_data = {
                                        delete_id: delete_id,
                                        image_name: image_name
                                    };
                                    $.ajax({
                                        beforeSend: function(xhr) {
                                            xhr.setRequestHeader('Authorization', auth);
                                        },
                                        url: appUrl+'Api/DeleteMyImage',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: delete_data,
                                        success: function(r) {
                                            if (r.code == 200) {
                                                swal("Selected File Deleted", {
                                                    icon: "success",
                                                });
                                                ShowUploadedImage(auth);
                                            }
                                        }
                                    });
                                } else {
                                    // swal("Your imaginary file is safe!");
                                }
                            });
                        })
                    } else {
                        $('#Myimages').html("No images Found");
                        $('#nav-about-tab').click();
                    }
                }
            });
        } else {
            $('#loader-main').hide();
            $('#nav-about-tab').click();
        }
    }

    function single_product(response) {

        var html = '';
        if (response) {
            html += '<div class="pane-content">\
                          <h2><span class="productName">' + response.title + '</span></h2>\
                          <div class="digitalInkContainer">\
                            <p><strong>Available Colors</strong></p>\
                            <div class="ProductColorPicker">';
            $(response.color_ids).each(function(i, hex) {
                if (i == 0) {
                    html += '<label for="color-' + hex.replace('#', '') + '" class="colorBox" data-color="' + hex + '" style="background-color: ' + hex + ';top:7px;"></label>';
                } else {
                    html += '<label for="color-' + hex.replace('#', '') + '" class="colorBox" data-color="' + hex + '" style="background-color: ' + hex + ';"></label>';
                }
                html += '<input id="color-' + hex.replace('#', '') + '" type="radio" name="color" value="' + hex.replace('#', '') + '">';
            });
            html += '</div>\
                          </div>\
                          <div class="btn-container"><button data-toggle="modal" data-target="#productModal" class="findProduct btn btn-primary">Change Product</button></div>\
                            <p><strong>Sizes</strong></p>\
                            <div class="ProductSizePicker">';
            $(response.size_ids).each(function(i, size) {
                html += '<label for="size-' + size + '" class="sizeBox" data-product_size="' + size + '">' + size + '</label>';
                html += '<input id="size-' + size + '" type="radio" name="size" value="' + size + '">';
            });
            html += '</div>';
						html += '<input type="hidden" id="SelectedVariantId" value="'+response.variant_id+'">';
            html += '<p class="description">' + response.description + '</p>\
            </div>';
            $('.front-image').html('<canvas id="front-canvas"></canvas>');
            $('.back-image').html('<canvas id="back-canvas"></canvas>');
            initiate_canvas('front-canvas', response.front_image, true);
            initiate_canvas('back-canvas', response.back_image);
            window['current-canvas']='front-canvas';
            BindCanvasEvents();
        }
      $('#product-details').html(html);
      $('#loader-main').hide();
    }
    $(document).on('click','.findProduct',function() {
        product_selector();
    });
    $(document).on('click','.colorBox',function(e) {
        e.preventDefault();
        var color = $(this).data('color');
        $('.image-box .lower-canvas').css('background-color', color);
    });

    $(document).on('click','.sizeBox',function(e) {
        e.preventDefault();
        var size = $(this).data('product_size');
    });
    $(document).on('click','.ProductSizePicker label',function(e) {
        $('.ProductSizePicker label').removeClass('active');
        $(this).addClass('active');
    });
    $(document).on('click','.ProductColorPicker label',function(e) {
        $('.ProductColorPicker label').removeClass('active');
        $(this).addClass('active');
    });
    $(document).on('click','.product',function() {
        var pr_id = $(this).data('product-id');
        selected_product(pr_id);
    });

    function product_selector() {
        $('#loader-main').show();
        $.ajax({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', auth);
            },
            url: appUrl+'Api/get_all_products',
            type: 'get',
            dataType: 'json',
            success: function(r) {
                var products = '';
                $(r).each(function(i, product) {
                    products += '<div class="product col-md-4" data-variant-id="' + product.variant_id + '" data-product-id="' + product.product_id + '" data-selected-color="WHT" data-name="' + product.title + '" data-colors-desc="' + product.color_ids.length + ' Colors">\
                                                <div class="product-img">\
                                                    <a href="#"><img src="' + product.front_image + '" alt="' + product.title + '"></a>\
                                                </div>\
                                                <div class="info"><div class="name">' + product.title + '</div>\
                                                <div class="colors-qty"><span class="colors-desc">' + product.color_ids.length + ' Colors</span></div>\
                                              <div class="pro-price"><span>$5.70</span> each &nbsp;-&nbsp; 100 qty *</div>\
                                            </div>\
                                        </div>';
                });
                $('#product-selector').html(products);
                $('#loader-main').hide();
            }
        });
    }

    function selected_product(pr_id) {
        $.ajax({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', auth);
            },
            url: appUrl+'product/' + pr_id,
            type: 'get',
            dataType: 'json',
            contentType: "application/json",
            success: function(response) {
                single_product(response);
                $('[data-dismiss="modal"]').trigger('click');
            }
        });
    }



    function clipart_categories(auth) {
        $('#loader-main').show();
        $.ajax({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', auth);
            },
            url: appUrl+'Api/get_categories',
            type: 'get',
            dataType: 'json',
            contentType: "application/json",
            success: function(r) {
                var cat_html = '';
                if (r.categories) {
                    var cats = r.categories;
                    cat_html += '<div class="row m-0 category-tab" id="parent-category">';
                    for (var title in cats) {
                        if (!cats.hasOwnProperty(title)) continue;
                        var obj = cats[title];
                        cat_html += '<div data-src="' + cats[title].image + '" class="col-md-3 card parent_cat ' + (obj.hasOwnProperty('sub_category') ? 'has_child_cat' : '') + '" data-id="' + cats[title].id + '" data-title="' + title + '">';
                        cat_html += '<div class="categoryButton">\
                                        <img class="card-img-top" src="' + cats[title].thumb + '">\
                                        <div class="card-text">' + title + '</div>\
                                    </div>';
                        cat_html += '</div>';
                    }
                    cat_html += '</div>';
                    cat_html += '<div id="child-categories" class="category-tab"></div>';
                    cat_html += '<div id="clip-arts" class="category-tab"></div>';
                }
                $('.category-tab').hide();
                $('#loadCliparts').html(cat_html);
                $('#parent-category').show();
                _breadcrumb();
                $('#loader-main').hide();
                $('.parent_cat').click(function() {
                    haschild_cat(cats, $(this));
                });
            },
						error:function (err) {
							if(err.responseJSON.code == 101){
								$('#loader-main').hide();
								$('#search-data').hide();
								$('#clipart-breadcrumb').text('No ClipArts Found');
							}
						}
        });
    }

    $(document).on('click','.child_cat',function() {
      $('#search-data').hide();
        var cat_id = $(this).data('id');
        var cat_title = $this.data('title');
        var sub_cat_title = $(this).data('title');
        get_cliparts(cat_id, cat_title, sub_cat_title);
    });

    function haschild_cat(cats, $this) {
        $('#loader-main').show();
        if ($this.hasClass('has_child_cat')) {
            var sub_cats = cats[$this.data('title')]['sub_category'],
                cat_html = '';
            cat_html += '<div class="row m-0">';
            $(sub_cats).each(function(i, sub_cat) {
                cat_html += '<div data-src="' + sub_cat[title].image + '" class="col-md-3 card child_cat" data-id="' + sub_cat.id + '" data-title="' + sub_cat.title + '">';
                cat_html += '<div class="categoryButton">\
                                <img class="card-img-top" src="' + sub_cat.thumb + '">\
                                <div class="card-text">' + sub_cat.title + '</div>\
                            </div>';
                cat_html += '</div>';
            });
            cat_html += '</div>';
            $('.category-tab').hide();
            $('#child-categories').show().html(cat_html);
            _breadcrumb($this.data('title'));
            $('#loader-main').hide();
        } else {
					$('#search-data').hide();
            var cat_id = $this.data('id');
            var cat_title = $this.data('title');
            get_cliparts(cat_id, cat_title);
        }
    }

    function get_cliparts(cat_id, cat_title, sub_cat_title = '') {
        $('#loader-main').show();
        $.ajax({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', auth);
            },
            url: appUrl+'Api/get_cliparts',
            type: 'post',
            data: {
                cat_id: cat_id
            },
            dataType: 'json',
            success: function(r) {
                var cat_html = '';
                if (r) {
                    cat_html += '<div class="row m-0" id="cliparts-images">';
                    $(r).each(function(i, obj) {
                        cat_html += '<div data-src="' + obj.image + '" class="col-md-3 card">';
                        cat_html += '<div class="clipart-button">\
                                        <img class="card-img-top" src="' + obj.thumb + '">\
                                    </div>';
                        cat_html += '</div>';
                    });
                    cat_html += '</div>';
                }
                $('.category-tab').hide();
                $('#clip-arts').show();
                _breadcrumb(cat_title, sub_cat_title);
                $('#clip-arts').html(cat_html);
                $('#loader-main').hide();
            }
        });
    }
		function SetActiveObject(canvas, object_id,color="") {
				canvas.getObjects().forEach(function(obj) {
						if (obj.myid === object_id) {
								canvas.setActiveObject(obj);
								obj.setColor(color);
						}
				});
		}

    function _breadcrumb(cat_title = '', sub_cat_title = '') {
        var breadcrumb = '';
        if (sub_cat_title != '') {
            breadcrumb = '<li class="breadcrumb-item item-1"><a href="#">Clip Arts</a></li>\
                        <li class="breadcrumb-item item-2"><a href="#">' + cat_title + '</a></li>\
                        <li class="breadcrumb-item item-3 active">' + sub_cat_title + '</li>';
        } else {
            breadcrumb = '<li class="breadcrumb-item item-1"><a href="#">Clip Arts</a></li>\
                        <li class="breadcrumb-item item-2 active">' + cat_title + '</li>';
        }
        if (cat_title == '' && sub_cat_title == '') {
            breadcrumb = '<li class="breadcrumb-item item-1 active">Clip Arts</li>';
        }
        $('#clipart-breadcrumb ol').html(breadcrumb);
        clik_item1();
        clik_item2(cat_title)
    }

    function clik_item1() {

    }

    function clik_item2(cat_title) {
        $('.item-2 a').click(function(e) {
            e.preventDefault();
            $('.category-tab').hide();
            $('#child-categories').show();
            $('#clipart-breadcrumb ol').html('<li class="breadcrumb-item item-1"><a href="#">Clip Arts</a></li>\
            <li class="breadcrumb-item item-2 active">' + cat_title + '</li>');
            clik_item1();
        });
    }
    $(document).on('click','.item-1 a',function(e) {
        e.preventDefault();
        $('.category-tab').hide();
        $('#parent-category').show();
        $('#clipart-breadcrumb ol').html('<li class="breadcrumb-item active item-1">Clip Arts</li>');
    });


    $(document).on('click','.rotateButton',function(event) {
        event.preventDefault();
        $('.box-front').toggleClass('flip-front');
        $('.box-back').toggleClass('flip-back');
        var classes = $('.front-image').attr('class').split(' ');
        if (parseInt(classes.indexOf("flip-front")) > 0) {
            $(this).html('view front');
            window['current-canvas']='back-canvas';
        } else {
            window['current-canvas']='front-canvas';
            $(this).html('view back');
        }
        $('#ImgCustom,.text_styles').hide();
        $('.addText').show();
        $('#text-editor').val('');
        window[window['current-canvas']].deactivateAll().renderAll();
        BindCanvasEvents();
    });

    $(document).on('click','.zoomButton',function(event) {
        var classes = $('.canvas-container').attr('class').split(' ');
        if (parseInt(classes.indexOf("zoom_in")) > 0) {
            $('.canvas-container').removeClass('zoom_in');
            $("#product_details").css("z-index", '');
            $(this).html('Zoom In');
        } else {
            $('.canvas-container').addClass('zoom_in');
            $('#product_details').css('z-index', '9999');
            $(this).html('Zoom Out');
        }
    });

    $(document).on('click','.clearButton',function(event) {
        var canvas = window[window['current-canvas']];
            swal({
                title: "Are you sure?",
                text: "To clear the design?",
                icon: "warning",
                buttons: ["No", "Clear"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    if (canvas._objects.length > 0){
                      canvas.discardActiveObject();
                      canvas.getObjects().forEach((obj) => {
                        canvas.remove(obj)
                      });
                      canvas.renderAll()
                    }
                    $('#text-editor').val('');
                    $("#menu1").html('Select Font Family <span class="caret"></span>');
                    $('.addText').show();
                    $('.text_styles').hide();

                    if($('.ProductColorPicker label').hasClass('active')){
                       $('.ProductColorPicker label').removeClass('active')
                    }
                    $("#front-canvas").css("background-color", "");
                    $("#back-canvas").css("background-color", "");
                    swal("All Designs Are clered", {
                        icon: "success",
                    });
                } else {
                }
            });
    });




    function initiate_canvas(selector_id, img_url, _text_editor = null) {
        var img = new Image();
        img.src = img_url;
        var old_canvas = window[selector_id];
        var old_found = false;
        if (old_canvas._objects && old_canvas._objects.length > 0) {
            old_found = true;
            var old_objects = old_canvas._objects
        }
        var canvas = window[selector_id] = new fabric.Canvas(selector_id);
        canvas.setHeight(570);
        canvas.setWidth(545);
						fabric.Image.fromURL(img_url, function(img) {
								canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas),{
										scaleX: canvas.width / img.width,
										scaleY: canvas.height / img.height,
								});
						}, {
								crossOrigin: 'anonymous'
						});
						canvas.renderAll();

        if (old_found) {
            addOldObjects(canvas, old_objects)
        }
        _customize_controls(canvas);

    }

    function addOldObjects(canvas, objects) {
        objects.forEach(function(obj) {
            canvas.add(obj);
        });
        canvas.renderAll();
    }
    jQuery(function() {
			if ($('div#drop_1').length) {
        var myDropzone = new Dropzone("div#drop_1", {
            url: appUrl+"Api/UploadMyimages",
            addRemoveLinks: true,
            maxFilesize: 1,
            maxFiles: 100,
            uploadMultiple: false,
            parallelUploads: 100,
            createImageThumbnails: true,
            paramName: "file",
            autoProcessQueue: false,
            headers: {
                'Authorization': auth,
                'Cache-Control': null,
                'X-Requested-With': null
            }
        });
        var submitButton = document.querySelector("#submit_job");
        submitButton.addEventListener("click", function() {
            var data_object = [];
            myDropzone.on("sending", function(file, xhr, formData) {
                formData.append("customer_email", $("#customer_email").val());
                formData.append("customer_id", $('#customer_id').val());
                formData.append("file", file);
            });
            myDropzone.processQueue();
            myDropzone.on("complete", function(file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    swal("All Images Uploaded", {
                        icon: "success",
                    });
                    $('[data-dismiss="modal"]').trigger('click');
                    ShowUploadedImage(auth);
                }
            });
        });
			}
    });


    function GetActiveCanvasStatus(canvas, object) {
        var active = false;
        canvas.getObjects().forEach(function(obj) {
            if (obj.myid === object.myid) {
                active = true;
            }
        });
        return active;
    }

    function SetSliderValues(SliderClass, value) {
        $("." + SliderClass).val(value)
    }

    function SetSelectedValues(object = null) {
        var inputs = ["sizeInput", "trackingInput", "rotInput", "leadingInput", "horizontalScaleInput","uwidthInput", "uHeightInput", "urotInput", "uhorizontalScaleInput"];
        if (object) {
            var values_array = [];
            values_array.push(object.originalState.fontSize);
            values_array.push(object.__charSpacing);
            values_array.push(parseInt(object.originalState.angle));
            values_array.push(parseInt(object.originalState.lineHeight));
            values_array.push(parseInt(object.originalState.scaleX));
            $(inputs).each(function(index, input) {
                $('#' + input).val(values_array[index]);
                $('.' + input).val(values_array[index]);
            });
            $("#menu1").html('<span style="font-family: ' + object.originalState.fontFamily + ';">' + object.originalState.fontFamily + '</span><span class="caret"></span>');
            if (object.originalState.fill) {
                $('#color-modifier input').val(object.originalState.fill);
            }
            if (object.originalState.stroke) {
                $('#color-stroke input').val(object.originalState.stroke);
            }
            $('#text-editor').val(object.text);
            if (object.originalState.shadow) {
                $('#color-shadow input').val(object.originalState.shadow.color);
            }
            $('.addText').hide();
            $('.text_styles').show();
        } else {
            $(inputs).each(function(index, input) {
                $('#' + input).val('');
                $('.' + input).val($('.' + input).attr('min'));
            });
            $('#color-modifier input').val('');
            $('#color-stroke input').val('');
            $('#color-shadow input').val('');
            $('#text-editor').val('');
            $("#menu1").html('Select Font Family <span class="caret"></span>');
            $('.addText').show();
            $('.text_styles').hide();
        }
    }

    function SetCanvasTextParams(obj,canvas,param,value){
        if (param == 'color') {
          obj.setColor(value);
        } else {
          obj.set(param, value);
        }
        canvas.renderAll();
    }
    function SetObjectText(obj,canvas,value){
      if(obj){
        obj.setText(value);
        canvas.renderAll();
      }
    }

    $(document).on('keyup','#text-editor',function() {
      var canvas = window[window['current-canvas']];
      SetObjectText(canvas.getActiveObject(),canvas,$(this).val());
      $('textarea#text-editor').css("border-color", "#949494");
    })


    $(document).on('change','.colors',function() {
        var canvas = window[window['current-canvas']];
        $setValue = $(this).val();
        if ($(this).attr('attr_name') == 'shadow') {
          $setValue = '' + $(this).val() + ' -2px -2px 3px';
        }
        SetCanvasTextParams(canvas.getActiveObject(),canvas,$(this).attr('attr_name'),$setValue);
    });
    $(document).on('input','.style_slider',function(e) {
        var txb = $(this).attr('txb_id');
          var canvas = window[window['current-canvas']];
        var param = $(this).closest('tr').find('#' + txb).attr('obj_attr');
    		var apply = $(this).parents('.parent_table').data('apply');
    		if (apply == 'text'){
          SetCanvasTextParams(canvas.getActiveObject(),canvas,param,parseInt($(this).val()));
    		}else{
          SetCanvasTextParams(canvas.getActiveObject(),canvas,param,parseInt($(this).val()));
    		}
        $(this).closest('tr').find("#" + txb).val(parseInt($(this).val()));
    });

    $(document).on('keyup keypress blur change','.tr_input',function(evt) {
        var canvas = window[window['current-canvas']];
       var charCode = (evt.which) ? evt.which : event.keyCode
       if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
           return false;
       }else{
          SetSliderValues($(this).attr('id'), parseInt($(this).val()));
          SetCanvasTextParams(canvas.getActiveObject(),canvas,$(this).attr('obj_attr'),parseInt($(this).val()));
       }
   });
   $(document).on('click','.font_style',function(){
     var canvas = window[window['current-canvas']];
       var font = $(this).attr('font_style').replace(/\+/g, ' ');
       font = font.split(':')[0];
       SetCanvasTextParams(canvas.getActiveObject(),canvas,'fontFamily',font);
       $("#menu1").html('<span style="font-family: ' + font + ';">' + font + '</span><span class="caret"></span>');
   });

   function BindCanvasEvents() {
     var canvas = window[window['current-canvas']];
     canvas.on('mouse:down', function(options) {
         if (options.target) {
             if (GetActiveCanvasStatus(canvas, canvas.getActiveObject())) {
     						var objtype = canvas.getActiveObject().objecttype;
     						if (objtype == 'text') {
     							$('#product_details .nav-item').eq(2).click();
     							SetSelectedValues(canvas.getActiveObject());
     						}else{
     							showImgCustom();
     							$('#product_details .nav-item').eq(1).click();
     							$('#product_details .nav-click').eq(4).click();
     							SetSelectedValuesImage(canvas.getActiveObject());
     						}
             } else {
                 SetSelectedValues(null)
     						hideImgCustom();
             }
         }else{
             SetSelectedValues(null)
     				hideImgCustom();
         }
     });
   }

    $(document).on('click','.addText',function(){
      var canvas = window[window['current-canvas']];
      var text_color = $('.color-input').val();
        if ($('textarea#text-editor').val() != ''){
        var newID = (new Date()).getTime().toString().substr(5);
        var this_text = $('textarea#text-editor').val();
        var text = new fabric.IText(this_text, {
            fontFamily: 'arial black',
            left: 100,
            top: 100,
            padding:20,
            fontSize: 20,
            myid: newID,
            objecttype: 'text'
        });
        canvas.add(text);
        SetActiveObject(canvas, newID, text_color);
        SetSelectedValues(canvas.getActiveObject());
          $(".addText").hide();
          $('.text_styles').show();
          $('textarea#text-editor').css("border-color", "#949494");
        } else {
          $('textarea#text-editor').css("border-color", "red");
          HideTextControlls();
        }

    })


    function HideTextControlls() {
        $(".addText").show();
        $('.text_styles').hide();
    }

		function SetSelectedValuesImage(object = null) {
			var inputs = ["widthInput", "HeightInput", "crotInput", "chorizontalScaleInput"];
			if (object) {
				var values_array = [];
				values_array.push(object.originalState.width);
				values_array.push(object.originalState.height);
				values_array.push(parseInt(object.originalState.angle));
				values_array.push(parseInt(object.originalState.scaleX));
				$(inputs).each(function(index, input) {
						$('#' + input).val(values_array[index]);
						$('.' + input).val(values_array[index]);
				});
			}else{
				hideImgCustom();
			}
		}

});
}
}
